<?php
//Settings
$serverprefix = "TEH WARRiORS | ";
$renttitle = "TEH WARRiORS UTHYRNINGSSYSTEM";
$maxservers = 10;


//SETTINGS END
//DO NOT EDIT BELOW THIS LINE IF NOT INTENDED!

$error = false;

//Få fram medlemsidt för att kolla om personen har bokat en server redan.
$user_id = 1;

//I demomiljön är group 1 medlemsgruppen.


//Kolla om medlemmen har en server.
if ($result = mysql_query("SELECT * FROM bokningar WHERE medlemsid = '$user_id'")) {
	$antalmedservrar = mysql_num_rows($result);
    if ($antalmedservrar) {
    	$memberhasserver = 1;

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	$server['ip'] = $row[1];
    	$server['name'] = $row[2];
    	$server['losen'] = $row[3];
    	$server['rcon'] = $row[4];
    	$server['spel'] = $row[5];
    	$server['starttid'] = $row[7];
    	$server['stoptid'] = $row[8];
	}


    	
    } else {
    	$memberhasserver = 0;
    }
    /* free result set */
    mysql_free_result($result);
} else {
	
die ('<!doctype html>
<html lang="sv">
<head>
	<meta charset="UTF-8">
	<title>Boka en server</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="wrapper">
		<div class="info">
		<h3 class="error">DATABASFEL!</h2>
		</div>
	</div>
</body>
</html>');
}
?>
<!doctype html>
<html lang="sv">
<head>
	<meta charset="UTF-8">
	<title>Boka en server</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="wrapper">
		<h1><?php echo $renttitle; ?></h1>
		<div class="info">
	<?php


//Bokning:

//Först kollar vi om vi ska boka eller avboka servern eller bara visa formuläret.
if (isset($_POST['boka'])) {
	if ($memberhasserver) {
		critical_error('Du har redan bokat en server!');
	} else {
		//Kollar om man angett fälten Namn, PW och rcon.
		if (!isset($_POST['namn']) or !isset($_POST['pw']) or !isset($_POST['rcon']) or $_POST['namn'] == "" or $_POST['pw'] == "" or $_POST['rcon'] == "") {
			//Om användaren "glömt" ange lösen, servernamn eller rcon
			$error .= "<h3 class=\"error\">Du glömde att ange Servernamn, Lösenord eller Rconlösenord!</h2>";
		} else {
			//Laddar värden från formuläret
			$servernamn = $serverprefix . $_POST['namn'];
			$serverlosen = $_POST['pw'];
			$serverrcon = $_POST['rcon'];
			$serverspel = $_POST['spel'];

			switch ($serverspel) {
				case 'css':
					$databasspel = "Counter Strike: Source";
					break;
				case 'csgo':
					$databasspel = "Counter Strike: Global Offensive";
					break;
				case 'cs16':
					$databasspel = "Counter Strike 1.6";
					break;
				default:
					$databasspel = false;
					break;
			}
			
			//Alla teckenlängder har att göra med databasen, Tabellen innehåller 
			if (strlen($servernamn) > 30) {
				//Kollar om servernamet är längre än 30 tecken. (TW.NET inkluderat.)
				$error .= "<h3 class=\"error\">Servernamnet är för långt!</h2>";
			} elseif (strlen($serverlosen) > 30) {
				//Kollar om lösenordet är längre än 30 tecken.
				$error .= "<h3 class=\"error\">Lösenordet är för långt!</h2>";
			} elseif (strlen($serverrcon) > 30) {
				//Kollar om rcon lösenordet är längre än 30 tecken.
				$error .= "<h3 class=\"error\">Rconlösenordet är för långt!</h2>";
			} elseif (!$databasspel){
				$error .= "<h3 class=\"error\">Speltypen finns inte! Försöker du göra något dumt eller gjorde du det av misstag? :O</h2>";
			} else {
				//Om alla fält är "lagom långa"^^
				echo "<p>Name: " . $servernamn . "<br>Losen: " . $serverlosen . "<br>Rcon: " . $serverrcon . "<br>Spel: " . $serverspel . "</p>";
				//Kolla portar etc...
				if ($result = mysql_query("SELECT * FROM bokningar")) {
					$antalservrar = mysql_num_rows($result);
				    if ($antalservrar >= $maxservers) {
				    	$error .= "<h3 class=\"error\">Alla servrar är redan bokade</h3>";
				    } else {
				    	//Det finns lediga servrar.



				    	//Få IP + port från Ozzzkars script?



				    	mysql_query("INSERT INTO `serverbokning`.`bokningar` (`id`, `ip`, `namn`, `losen`, `rcon`, `spel`, `medlemsid`, `starttid`, `sluttid`) VALUES (NULL, '123.123.123.123:25612', '$servernamn', '$serverlosen', '$serverrcon', '$serverspel', '$user_id', CURRENT_TIMESTAMP, '0000-00-00 00:00:00');")






				    }
				    /* free result set */
				    mysql_free_result($result);
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
		<td><label for="pw">Lösenord: </label></td><td class="fullw"><input class="text" type="text" id="pw" name="pw" value="{$pwvalue}" placeholder="Serverlösenord"></td>
	</tr>
	<tr>
		<td><label for="rcon">Rconlösen: </label></td><td class="fullw"><input class="text" type="text" id="rcon" name="rcon" value="{$rconvalue}" placeholder="Rconlösenord"></td>
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
		if (mysql_query("DELETE * FROM bokningar WHERE memberid = '$user_id'")) {
			//Borttagen ur databasen. Server stopscript ska köras.


			/*
				HÄR OSKAR SKA DU ÄNDRA! :)

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

//Om personen är medlem kolla om personen redan har bokat en server.
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
		<td><label for="pw">Lösenord: </label></td><td class="fullw"><input class="text" type="text" id="pw" name="pw" value="{$pwvalue}" placeholder="Serverlösenord"></td>
	</tr>
	<tr>
		<td><label for="rcon">Rconlösen: </label></td><td class="fullw"><input class="text" type="text" id="rcon" name="rcon" value="{$rconvalue}" placeholder="Rconlösenord"></td>
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
	<!-- Visa om server redan är bokad -->
	
		<h2>Du har bokat en server.</h2>
		<p>
			Servernamn: {$server['name']}<br>
			IP&amp;Port: {$server['ip']}<br>
			Console connect: <span class="connectstring">connect {$server['ip']};password {$server['losen']}</span><br>
			Serverlösen: {$server['losen']}<br>
			Rconlösen: {$server['rcon']}<br>
			Spel: {$server['spel']}<br>
			Starttid: {$server['starttid']}<br>
			Stoptid: {$server['stoptid']}
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




?>
</div>
</div>
</body>
</html>
