<?php
//Settings
$renttitle = "TEH WARRiORS RENTING SYSTEM";

$medlemsgrupp = 1;
//Denna används för att jämföras med vilken grupp medlemmen är i. Om gruppen stämmer med medlemmen är medlemmen en aktiv medlem.

//Mysql
$mysqli = new mysqli("127.0.0.1","serverbokning","serverbokning","serverbokning");

//Få fram medlemsidt för att kolla om personen har bokat en server redan.
$memberid = 1;
$membergroup = 1;

//I demomiljön är group 1 medlemsgruppen.


$memberhasserver = 0;
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
		<td><label for="namn">Servernamn: </label></td><td><input class="text" type="text" id="namn" name="namn" value="{$namnvalue}" placeholder="Namn"></td>
	</tr>
	<tr>
		<td><label for="pw">Lösenord: </label></td><td><input class="text" type="text" id="pw" name="pw" value="{$pwvalue}" placeholder="Serverlösenord"></td>
	</tr>
	<tr>
		<td><label for="rcon">Rconlösen: </label></td><td><input class="text" type="text" id="rcon" name="rcon" value="{$rconvalue}" placeholder="Rconlösenord"></td>
	</tr>
	<tr>
		<td><label for="spel">Spel: </label></td>
		<td><select name="spel" id="spel" class="option">
		  <option value="css" name="css">Counter Strike: Source</option> 
		  <option value="csgo" name="csgo">Counter Strike: Global Offensive</option>
		  <option value="cs16">Counter Strike: 1.6</option>
		</select>
		</td>
	</tr>
	<tr>
		<td><input type="submit" value="Boka" id="boka" name="boka"></td>
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
			Spel: CSS
		</p>
	</div>
	<form action="" method="POST">
		<input type="submit" value="Avboka" name="avboka">
	</form>
EOD;

}

	} else {
		//Körs om personen inte är medlem.
		echo "Du är inte medlem<br>";
		echo "Klicka <a href=\"#\">Här för att bli medlem</a>";
	}


//Form checks ...


//Bokning:

//Först kollar vi om vi ska boka eller avboka servern eller bara visa formuläret.
if (isset($_POST['boka'])) {
	//Kollar om man angett fälten Namn, PW och rcon.
	if (!isset($_POST['namn']) or !isset($_POST['pw']) or !isset($_POST['rcon']) or $_POST['namn'] == "" or $_POST['pw'] == "" or $_POST['rcon'] == "") {
		//Om användaren "glömt" ange lösen, servernamn eller rcon
		echo "Det verkar som att du glömt att ange Servernamn, Lösenord eller Rcon! Var god försök igen!";
	} else {
		//Laddar värden från formuläret
		$servernamn = "TEH WARRiORS | " . $_POST['namn'];
		$serverlosen = $_POST['pw'];
		$serverrcon = $_POST['rcon'];
		$serverspel = $_POST['spel'];
		echo "Name: " . $servernamn . "<br>Losen: " . $serverlosen . "<br>Rcon: " . $serverrcon . "<br>Spel: " . $serverspel;
		//Alla teckenlängder har att göra med databasen, Tabellen innehåller 
		if (strlen($servernamn) > 30) {
			//Kollar om servernamet är längre än 30 tecken. (TW.NET inkluderat.)
			echo "Servernamnet är för långt.";
		} elseif (strlen($serverlosen) > 30) {
			//Kollar om lösenordet är längre än 30 tecken.
			echo "Serverlösenordet är för långt";
		} elseif (strlen($serverrcon) > 30) {
			//Kollar om rcon lösenordet är längre än 30 tecken.
			echo "Serverrconet är för långt.";
		}
	}
}




//Avbokning

if (isset($_POST['avboka'])) {


}


?>
</div>
</body>
</html>
