<?php

function template_main()
{
	global $context, $settings, $options, $txt, $modsettings, $scripturl, $user_profile, $error;
	$error = false;
	@loadMemberContext();
	echo <<<STL
	<style type="text/css">
	/*
STYLE.CSS f�r serveruthyrningen
*/
/* FONT IMPORTS (Using // for links to make sure HTTPS is used when needed.) */
@import url(//fonts.googleapis.com/css?family=Open+Sans:400,300,700,600);
@import url(//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600);



#wrapper {
	margin: 3px;
	/* Centrerar inneh�llswrappern */
	max-width: 1000px;

}


p, form, input.text, option, .option {
	font-family: 'Source Sans Pro', sans-serif;
	font-weight: 500;
	font-size: 15pt;
	
}

input.text, option, .option {
	background: rgba(0,0,0, 0.1);
	border: 0 none;
	color: black;
	font-size: 15pt;
	line-height: 1.5;
	text-align: left;
	border-radius: 5px;
	padding: 1%;
	margin-top: 0.5%;
}

option, .option {
	width: 100%;
}

input.text {
	width: 98%;
}


.error {
	color: red;
	font-family: 'Open Sans', sans-serif;
	font-weight: 300;
	text-align: center;
	font-size: 20pt;
}


.rub1 {
	font-family: 'Open Sans', sans-serif;
	font-weight: 300;
	text-align: center;
	font-size: 30pt;
}

.rub2 {
	font-family: 'Open Sans', sans-serif;
	font-weight: 300;
	font-size: 25pt;

	
}

.submit {
	background: rgba(0,0,0, 0.1);
	border: 0 none;
	color: black;
	font-size: 20pt;
	line-height: 1.5;
	text-align: left;
	border-radius: 5px;
	padding: 1%;
	margin-top: 0.5%;
	width: 100%;
	text-align: center;

}

.fullw {
	width: 100%;

}

.connectstring {
	color: lightblue;
	
}



	</style>

STL;
	

	

	$user_id = $context['user']['id'];
	@loadMemberData($user_id);
	$is_guest = (isset($context['user']['is_guest']) && $context['user']['is_guest']);

	if (!$is_guest)
	{
		$sql = "SELECT additional_groups FROM smf_members WHERE id_member = $user_id";
		$result = mysql_query($sql);
		$additional_groups_string = trim(mysql_result($result, 0));
		$additional_groups = explode(',', $additional_groups_string);
		if (in_array('9', $additional_groups) || $user_profile[$user_id]['id_group'] == '9')
		{
			render_page($user_id);
		}
		else
		{
			critical_error('Access denied. You\'re not a member.');
		}
	}
	else
	{
		critical_error('Access denied.');
	}
}


function critical_error($message)
{
	$error .= '<h3 class="error">' . $message . '</h3>';
	echo $error;
}

function render_page($user_id)
{

	
	$tablerun = "`serverbokning`.`bokningar`";

	//Settings
	$serverprefix = "TEH WARRiORS | ";
	$renttitle = "TEH WARRiORS UTHYRNINGSSYSTEM";
	$maxservers = 10;





	//echo 'Du �r anv�ndare nummer ' . $user_id . '.';

	
//Kolla om medlemmen har en server.
if ($result = mysql_query("SELECT * FROM $tablerun WHERE memberid = '$user_id'")) {
	$antalmedservrar = mysql_num_rows($result);
    if ($antalmedservrar) {
    	$memberhasserver = 1;

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	
    	$server['host'] = $row['server_address'];
    	$server['name'] = $row['name'];
    	$server['server_pw'] = $row['server_password'];
    	$server['rcon_pw'] = $row['rcon_password'];
    	$server['gametype'] = $row['gametype'];
    	$server['starttime'] = $row['starttime'];
    	$server['stoptime'] = $row['stoptime'];
	}


    	
    } else {
    	$memberhasserver = 0;
    }
    /* free result set */
    mysql_free_result($result);
} else {
	
die ('

		<div class="info">
		<h3 class="error">DATABASFEL!</h2>
		</div>
');
}


echo "<h1 class='rub1'>$renttitle</h1>";
echo '<div id="wrapper"><div class="info">';



//Bokning:

//F�rst kollar vi om vi ska boka eller avboka servern eller bara visa formul�ret.
if (isset($_POST['boka'])) {
	if ($memberhasserver) {
		critical_error('Du har redan bokat en server!');
	} else {
		//Kollar om man angett f�lten Namn, PW och rcon.
		if (!isset($_POST['namn']) or !isset($_POST['pw']) or !isset($_POST['rcon']) or $_POST['namn'] == "" or $_POST['pw'] == "" or $_POST['rcon'] == "") {
			//Om anv�ndaren "gl�mt" ange l�sen, servernamn eller rcon
			$error .= "<h3 class='error'>Du gl�mde att ange Servernamn, L�senord eller Rconl�senord!</h2>";
		} else {
			//Laddar v�rden fr�n formul�ret
			$srvname = $serverprefix . $_POST['namn'];
			$serverpassword = $_POST['pw'];
			$serverrcon = $_POST['rcon'];
			$srvgametype = $_POST['spel'];

			switch ($srvgametype) {
				case 'css':
					$dbgame = "Counter Strike: Source";
					break;
				case 'csgo':
					$dbgame = "Counter Strike: Global Offensive";
					break;
				case 'cs16':
					$dbgame = "Counter Strike 1.6";
					break;
				default:
					$dbgame = false;
					break;
			}
			
			//Alla teckenl�ngder har att g�ra med databasen
			if (strlen($srvname) > 30) {
				//Kollar om servernamet �r l�ngre �n 30 tecken. (TW.NET inkluderat.)
				$error .= "<h3 class='error'>Servernamnet �r f�r l�ngt!</h3>";
				
			} elseif (strlen($serverpassword) > 30) {
				//Kollar om l�senordet �r l�ngre �n 30 tecken.
				$error .= "<h3 class='error'>L�senordet �r f�r l�ngt!</h3>";
				
			} elseif (strlen($serverrcon) > 30) {
				//Kollar om rcon l�senordet �r l�ngre �n 30 tecken.
				$error .= "<h3 class='error'>Rconl�senordet �r f�r l�ngt!</h3>";
				
			} elseif (!$dbgame){
				$error .= "<h3 class='error'>Speltypen finns inte! F�rs�ker du g�ra n�got dumt eller gjorde du det av misstag? :O</h2>";
				
			} else {
				//Om alla f�lt �r "lagom l�nga"^^
				echo "<p>F�rs�ker att boka server med dessa egenskaper ...</p>";
				echo "<p>Name: " . $srvname . "<br>Losen: " . $serverpassword . "<br>Rcon: " . $serverrcon . "<br>Spel: " . $srvgametype . "</p>";
				//Kolla portar etc...
				if ($result = mysql_query("SELECT * FROM $tablerun")) {
					$antalservrar = mysql_num_rows($result);
					mysql_free_result($result);
				    if ($antalservrar >= $maxservers) {
				    	$error .= "<h3 class=\"error\">Alla servrar �r redan bokade</h3>";
				    } else {
				    	//Det finns lediga servrar.

				    		//echo "Bokning p�G.";

				    	//F� IP + port fr�n Ozzzkars script?

				    	if (mysql_query("INSERT INTO $tablerun (server_address, name, connect_password, rcon_password, gametype, memberid, starttime, stoptime) VALUES ('123.123.123.123:25612', '$srvname', '$serverpassword', '$serverrcon', '$srvgametype', '$user_id', CURRENT_TIMESTAMP, '0000-00-00 00:00:00')")) {
				    		echo "<p>Server bokad</p><p><a href=''>Klicka h�r f�r att forts�tta</a></p>";
				    	} else {
				    		echo "Databasfel";
				    	}
				    }				    
				}
			}
		}
	}

	if ($error) {
	if (isset($_POST['namn'])) {$namnvalue = $_POST['namn'];} else {$namnvalue = "";}
	if (isset($_POST['pw'])) {$pwvalue = $_POST['pw'];} else {$pwvalue = "";}
	if (isset($_POST['rcon'])) {$rconvalue = $_POST['rcon'];} else {$rconvalue = "";}
echo <<<EOD
	
	{$error}
	<form action="" method="POST">
	<table class="inputs">
	<tr>
		<td><label for="namn">Servernamn: </label></td><td class="fullw"><input class="text" type="text" id="namn" name="namn" value="{$namnvalue}" placeholder="Namn"></td>
	</tr>
	<tr>
		<td><label for="pw">L�senord: </label></td><td class="fullw"><input class="text" type="text" id="pw" name="pw" value="{$pwvalue}" placeholder="Serverl�senord"></td>
	</tr>
	<tr>
		<td><label for="rcon">Rconl�sen: </label></td><td class="fullw"><input class="text" type="text" id="rcon" name="rcon" value="{$rconvalue}" placeholder="Rconl�senord"></td>
	</tr>
	<tr>
		<td><label for="spel">Spel: </label></td>
		<td class="fullw"><select name="spel" id="spel" class="option">
		  <option value="css" name="css">Counter Strike: Source</option> 
		  <option value="csgo" name="csgo">Counter Strike: Global Offensive</option>
		  <option value="cs16">Counter Strike: 1.6</option>
		</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td class="fullw"><input class="submit" type="submit" value="Boka" id="boka" name="boka"></td>
	</tr>
		</table>
	</form>
	
EOD;

	}
}




//Avbokning

elseif (isset($_POST['avboka'])) {

	if ($memberhasserver) {
		if (mysql_query("DELETE FROM $tablerun WHERE memberid = '$user_id'")) {
			//Borttagen ur databasen. Server stopscript ska k�ras.


			/*
				H�R OSKAR SKA DU �NDRA! :)

				OSKAR?



				OSZKSKAR?


				PLZ :D


				EDIT :D


			*/

		} else {
			critical_error('Ett fel uppstod vid avbokningen.');
		}

	} else {
		critical_error('Du har ingen server att avboka.');
	}


} else {

//Om personen �r medlem kolla om personen redan har bokat en server.
if (!$memberhasserver){
	if (isset($_POST['namn'])) {$namnvalue = $_POST['namn'];} else {$namnvalue = "";}
	if (isset($_POST['pw'])) {$pwvalue = $_POST['pw'];} else {$pwvalue = "";}
	if (isset($_POST['rcon'])) {$rconvalue = $_POST['rcon'];} else {$rconvalue = "";}
echo <<<EOD
	
	<form action="" method="POST">
	<table class="inputs">
	<tr>
		<td><label for="namn">Servernamn: </label></td><td class="fullw"><input class="text" type="text" id="namn" name="namn" value="{$namnvalue}" placeholder="Namn"></td>
	</tr>
	<tr>
		<td><label for="pw">L�senord: </label></td><td class="fullw"><input class="text" type="text" id="pw" name="pw" value="{$pwvalue}" placeholder="Serverl�senord"></td>
	</tr>
	<tr>
		<td><label for="rcon">Rconl�sen: </label></td><td class="fullw"><input class="text" type="text" id="rcon" name="rcon" value="{$rconvalue}" placeholder="Rconl�senord"></td>
	</tr>
	<tr>
		<td><label for="spel">Spel: </label></td>
		<td class="fullw"><select name="spel" id="spel" class="option">
		  <option value="css" name="css">Counter Strike: Source</option> 
		  <option value="csgo" name="csgo">Counter Strike: Global Offensive</option>
		  <option value="cs16">Counter Strike: 1.6</option>
		</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td class="fullw"><input class="submit" type="submit" value="Boka" id="boka" name="boka"></td>
	</tr>
		</table>
	</form>
	
EOD;
} else {
echo <<<EOD
	<!-- Visa om server redan �r bokad -->
	
		<h2 class='rub2'>Du har bokat en server.</h2>
		<p>
			Servernamn: {$server['name']}<br>
			IP&amp;Port: {$server['host']}<br>
			Console connect: <span class="connectstring">connect {$server['host']};password {$server['server_pw']}</span><br>
			Serverl�sen: {$server['server_pw']}<br>
			Rconl�sen: {$server['rcon_pw']}<br>
			Spel: {$server['gametype']}<br>
			Starttid: {$server['starttime']}<br>
			Stoptid: {$server['stoptime']}
		</p>
	
	<form action="" method="POST">
		<input class="submit" type="submit" value="Avboka" name="avboka">
	</form>
	<form action="" method="POST">
		<input class="submit" type="submit" value="Starta om" name="startaom">
	</form>
EOD;

}

}

echo "</div></div>";

}





?>
