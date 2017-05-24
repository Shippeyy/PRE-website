<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SimpleFeatures</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

	<nav class="navbar navbar-default">
      <div class="container-fluid">
        <p class="navbar-text">SimpleFeatures</p>
        <a type="button" class="btn btn-default navbar-btn" href="main.php">Dashboard</a>
        <a type="button" class="btn btn-default navbar-btn" href="login.php">Login</a>
        <a type="button" class="btn btn-default navbar-btn" href="logout.php">Logout</a>
        <a type="button" class="btn btn-default navbar-btn" href="register.php">Register</a>
      </div>
    </nav>

<?php 
session_start();
include 'credentials.php'; //credentials for the db connection

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
catch(PDOException $e)
	{
	echo "Database connection failed: " . $e->getMessage();
	}



if(isset($_GET['register'])) {
 $error = false;
 $vorname = $_POST['vorname'];
 $nachname = $_POST['nachname'];
 $passwort = $_POST['passwort'];
  
 if(strlen($passwort) == 0) {
 echo 'Bitte ein Passwort angeben<br>';
 $error = true;
 }
 
 //Keine Fehler, wir können den Nutzer registrieren
 if(!$error) { 
 $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
 
 $sth = $conn->prepare('INSERT INTO personen (vorname, nachname, passwort, zugriffsLevel) VALUES (:vorname, :nachname, :passwort, :zugriffsLevel)');
 $sth->bindParam(':vorname', $vorname);
 $sth->bindParam(':nachname', $nachname);
 $sth->bindParam(':passwort', password_hash($passwort, PASSWORD_DEFAULT));
 $sth->bindParam(':zugriffsLevel', $zugriffsLevel = 1);
 $sth->execute();

 $findCreatedUser = $conn->prepare('SELECT vorname FROM personen WHERE vorname = :vorname AND nachname = :nachname');
 $findCreatedUser->bindParam(':vorname', $vorname);
 $findCreatedUser->bindParam(':nachname', $nachname);
 $findCreatedUser->execute();
 $result = $findCreatedUser->fetchAll();

 
 if($result) { 
 echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
 } else {
 echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
 }
 } 
}
?>

    <div class="container-fluid">
      <form action="?register=1" method="post">

      	<div class="input-group">
		  <span class="input-group-addon" id="basic-addon1">Eingabe</span>
		  <input type="text" class="form-control" placeholder="Vorname" aria-describedby="basic-addon1" name="vorname">
		</div>
		<br>
		<div class="input-group">
		  <span class="input-group-addon" id="basic-addon1">Eingabe</span>
		  <input type="text" class="form-control" placeholder="Nachname" aria-describedby="basic-addon1" name="nachname">
		</div>
		<br>
		<div class="input-group">
		  <span class="input-group-addon" id="basic-addon1">Eingabe</span>
		  <input type="password" class="form-control" placeholder="Passwort" aria-describedby="basic-addon1" name="passwort">
		</div>
		<br>
		<input type="submit" class="btn btn-default" value="Registrieren" />

      </form>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>