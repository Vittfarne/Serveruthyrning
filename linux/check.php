<?php
//Settings
$renttitle = "TEH WARRiORS UTHYRNINGSSYSTEM";
$mysql['host'] = "127.0.0.1"; //Enter the Servername or hostname of your database server.
$mysql['user'] = "serverbokning"; // Enter the username to be used when connecting to the database.
$mysql['pass'] = "serverbokning"; // Enter the password to be used when connecting to the database.
$mysql['db'] = "serverbokning"; // Enter the database to be used when connecting to the database.
$maxservers = 10;

$medlemsgrupp = 1;
//Denna används för att jämföras med vilken grupp medlemmen är i. Om gruppen stämmer med medlemmen är medlemmen en aktiv medlem.

//SETTINGS END
//DO NOT EDIT BELOW THIS LINE IF NOT INTENDED!

$error = false;


//Få fram medlemsidt för att kolla om personen har bokat en server redan.
$user_id = 1;
$membergroup = 1;

//I demomiljön är group 1 medlemsgruppen.



//Mysql
$mysqli = new mysqli($mysql['host'],$mysql['user'],$mysql['pass'],$mysql['db']);

//Kolla om medlemmen har en server.
if ($result = $mysqli->query("SELECT * FROM bokningar WHERE medlemsid = '$user_id'")) {
    if ($result->num_rows > 0) {
    	$memberhasserver = 1;

    	$server['name'] = "a";
    } else {
    	$memberhasserver = 0;
    }
    /* free result set */
    $result->close();
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
	<?php

	//Kolla om personen är medlem.
	if ($membergroup == $medlemsgrupp) {



	


//Form checks ...


//Bokning:

//Först kollar vi om vi ska boka eller avboka servern eller bara visa formuläret.
if (isset($_POST['boka'])) {
	//Kollar om man angett fälten Namn, PW och rcon.
	if (!isset($_POST['namn']) or !isset($_POST['pw']) or !isset($_POST['rcon']) or $_POST['namn'] == "" or $_POST['pw'] == "" or $_POST['rcon'] == "") {
		//Om användaren "glömt" ange lösen, servernamn eller rcon
		$error .= "<h3 class=\"error\">Du glömde att ange Servernamn, Lösenord eller Rconlösenord!</h2>";
	} else {
		//Laddar värden från formuläret
		$servernamn = "TEH WARRiORS | " . $_POST['namn'];
		$serverlosen = $_POST['pw'];
		$serverrcon = $_POST['rcon'];
		$serverspel = $_POST['spel'];
		
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
		} else {
			//Om alla fält är "lagom långa"^^
			echo "<p>Name: " . $servernamn . "<br>Losen: " . $serverlosen . "<br>Rcon: " . $serverrcon . "<br>Spel: " . $serverspel . "</p>";
			//Kolla portar etc...
			if ($result = $mysqli->query("SELECT * FROM bokningar")) {
			    if ($result->num_rows >= $maxservers) {
			    	$error .= "<h3 class=\"error\">Alla servrar är redan bokade</h3>";
			    } else {
			    	//Det finns lediga servrar.







			    }
			    /* free result set */
			    $result->close();
			}
		}
	}

	if ($error) {
	if (isset($_POST['namn'])) {$namnvalue = $_POST['namn'];} else {$namnvalue = "";}
	if (isset($_POST['pw'])) {$pwvalue = $_POST['pw'];} else {$pwvalue = "";}
	if (isset($_POST['rcon'])) {$rconvalue = $_POST['rcon'];} else {$rconvalue = "";}
echo <<<EOD
	<div class="info">
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
	</div>
EOD;

	}
}




//Avbokning

elseif (isset($_POST['avboka'])) {


} else {

//Om personen är medlem kolla om personen redan har bokat en server.
if (!$memberhasserver){
	if (isset($_POST['namn'])) {$namnvalue = $_POST['namn'];} else {$namnvalue = "";}
	if (isset($_POST['pw'])) {$pwvalue = $_POST['pw'];} else {$pwvalue = "";}
	if (isset($_POST['rcon'])) {$rconvalue = $_POST['rcon'];} else {$rconvalue = "";}
echo <<<EOD
	<div class="info">
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
	</div>
EOD;
} else {
echo <<<EOD
	<!-- Visa om server redan är bokad -->
	<div class="info">
		<h2>Du har bokat en server.</h2>
		<p>
			Servernamn: TEH WARRIORS.NET | MIN PCWSERVER<br>
			IP&amp;Port: 123.132.131.12:27045<br>
			Console connect: connect 123.132.131.12:27045;password mittpw
			Serverlösen: mittpw<br>
			Rconlösen: mittrcon<br>
			Spel: Counter Strike: Source
		</p>
	</div>
	<form action="" method="POST">
		<input class="submit" type="submit" value="Avboka" name="avboka">
	</form>
	<form action="" method="POST">
		<input class="submit" type="submit" value="Starta om" name="startaom">
	</form>
EOD;

}







}


} else {
		//Körs om personen inte är medlem.
		echo "<p>Du är inte medlem<br>Klicka <a href=\"#\">Här för att bli medlem</a></p>";
	}

?>
</div>
</body>
</html>
