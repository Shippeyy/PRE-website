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
	if(!isset($_SESSION['userid'])) {
	 die('<br> Bitte zuerst <a href="login.php">einloggen</a>');
	}

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

	$sth = $conn->prepare('SELECT id, titel, beschreibung, erstellDatum, p_besitzer, p_empfaenger FROM anforderungen
						   WHERE p_besitzer = :user OR p_empfaenger = :user');
	$sth->bindParam(':user', $_SESSION['userid']);
	$sth->execute();
	$anforderungen = $sth->fetchAll();

	$stmt = $conn->prepare('SELECT * FROM personen');
	$stmt->execute();
	$personen = $stmt->fetchAll();

	?>

    <div class="container-fluid">
    	<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelAddAnforderung">Anforderung hinzufügen</button>
		<button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#modelDeleteAnforderung">Anforderung entfernen</button>
		<br>
		<br>

		<!-- Modal -->
		<div class="modal fade" id="modelAddAnforderung" tabindex="-1" role="dialog" aria-labelledby="modelAddAnforderungLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="modelAddAnforderungLabel">Anforderung hinzufügen</h4>
		      </div>
		      <div class="modal-body">
		      	<form action="?addAnforderung=1" method="post">
			    	<div class="input-group">
			    		<span class="input-group-addon" id="basic-addon1">Eingabe</span>
			    		<input type="text" class="form-control" placeholder="Titel" aria-describedby="basic-addon1" name="titel">
			    	</div>
			    <br>
				    <div class="input-group">
				    	<span class="input-group-addon" id="basic-addon1">Eingabe</span>
				    	<input type="text" class="form-control" placeholder="Beschreibung" aria-describedby="basic-addon1" name="beschreibung">
				    </div>
			    <br>
				    <div class="input-group">
				    	<span class="input-group-addon" id="basic-addon1">Eingabe</span>
				    	<input type="date" class="form-control" placeholder="Erstelldatum" aria-describedby="basic-addon1" name="erstelldatum">
				    </div>
			    <br>
			    	<span class="input-group-addon" id="basic-addon1">Besitzer</span>
			    	<select name="besitzer" id="besitzer" aria-describedby="basic-addon1" class="form-control">
					  <option selected="selected">Choose one</option>
					  <?php
					    foreach($personen as $person) { ?>
					      <option value="<?=$person['id'] ?>"><?= $person['vorname'] . " " . $person['nachname'] ?></option>
					  <?php
					    } ?>
					</select>
				<br>
					<span class="input-group-addon" id="basic-addon1">Empf&auml;nger</span>
			    	<select name="empfaenger" id="empfaenger" aria-describedby="basic-addon1" class="form-control">
					  <option selected="selected">Choose one</option>
					  <?php
					    foreach($personen as $person) { ?>
					      <option value="<?=$person['id'] ?>"><?= $person['vorname'] . " " . $person['nachname'] ?></option>
					  <?php
					    } ?>
					</select>
				<br>
			    	<input type="submit" class="btn btn-default" value="Anforderung hinzufügen" />
			    </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modelDeleteAnforderung" tabindex="-1" role="dialog" aria-labelledby="modelDeleteAnforderungLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="modelDeleteAnforderungLabel">Anforderung entfernen</h4>
		      </div>
		      <div class="modal-body">
		      	<form action="?deleteAnforderung=1" method="post">
			    	<span class="input-group-addon" id="basic-addon1">Anforderung</span>
			    	<select name="targetAnforderung" id="targetAnforderung" aria-describedby="basic-addon1" class="form-control">
					  <option selected="selected">Choose one</option>
					  <?php
					    foreach($anforderungen as $anforderung) { ?>
					      <option value="<?=$anforderung['id'] ?>"><?= $anforderung['titel'] ?></option>
					  <?php
					    } ?>
					</select>
				<br>
			    	<input type="submit" class="btn btn-default" value="Anforderung entfernen" />
			    </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

<?php 
//add below
if(isset($_GET['addAnforderung'])) {
 $error = false;
 $titel = $_POST['titel'];
 $beschreibung = $_POST['beschreibung'];
 $erstelldatum = $_POST['erstelldatum'];
 $besitzer = $_POST['besitzer'];
 $empfaenger = $_POST['empfaenger'];
  
 if(strlen($titel) == 0 || strlen($beschreibung) == 0 || strlen($erstelldatum) == 0 || strlen($besitzer) == 0 || strlen($empfaenger) == 0) {
 echo 'Bitte alle Felder ausfüllen<br>';
 $error = true;
 }
 
 //Keine Fehler, wir können den Nutzer registrieren
 if(!$error) { 
 
 $createAnforderung = $conn->prepare('INSERT INTO anforderungen (titel, beschreibung, erstellDatum, p_besitzer, p_empfaenger)
 						VALUES (:titel, :beschreibung, :erstellDatum, :p_besitzer, :p_empfaenger)');
 $createAnforderung->bindParam(':titel', $titel);
 $createAnforderung->bindParam(':beschreibung', $beschreibung);
 $createAnforderung->bindParam(':erstellDatum', $erstelldatum);
 $createAnforderung->bindParam(':p_besitzer', $besitzer);
 $createAnforderung->bindParam(':p_empfaenger', $empfaenger);
 $createAnforderung->execute();

 $findCreatedAnforderung = $conn->prepare('SELECT titel FROM anforderungen
 										   WHERE titel = :titel
 										    AND beschreibung = :beschreibung
 										   	AND erstellDatum = :erstellDatum
 										   	AND p_besitzer = :p_besitzer
 										   	AND p_empfaenger = :p_empfaenger');
 $findCreatedAnforderung->bindParam(':titel', $titel);
 $findCreatedAnforderung->bindParam(':beschreibung', $beschreibung);
 $findCreatedAnforderung->bindParam(':erstellDatum', $erstelldatum);
 $findCreatedAnforderung->bindParam(':p_besitzer', $besitzer);
 $findCreatedAnforderung->bindParam(':p_empfaenger', $empfaenger);
 $findCreatedAnforderung->execute();
 $createdAnforderung = $findCreatedAnforderung->fetchAll();

 
 if($createdAnforderung) { 
 echo 'Die Anforderung wurde erfolgreich hinzugefügt<br>';
 } else {
 echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
 }
 } 
}

//delete below
if(isset($_GET['deleteAnforderung'])) {
 $error = false;
 $targetAnforderung = $_POST['targetAnforderung'];
  
 if(strlen($targetAnforderung) == 0) {
 echo 'Bitte alle Felder ausfüllen<br>';
 $error = true;
 }
 
 //Keine Fehler, wir können den Nutzer registrieren
 if(!$error) { 

 $deleteAnforderung = $conn->prepare('DELETE FROM anforderungen WHERE id = :targetAnforderung');
 $deleteAnforderung->bindParam(':targetAnforderung', $targetAnforderung);
 $deleteAnforderung->execute();
 
 } 
}
?>
		<!-- table -->
      	<table class="table table-hover">
	        <tr>
	          <th>Titel</th>
	          <th>Beschreibung</th>
	          <th>Erstelldatum</th>
	          <th>Besitzer</th>
	          <th>Empf&auml;nger</th>
	        </tr>
	        <? foreach ($anforderungen as $anforderung) : ?>
		    <tr>
		      <td><? echo $anforderung[1]; ?></td>
		      <td><? echo $anforderung[2]; ?></td>
		      <td><? echo $anforderung[3]; ?></td>
		      <td><? echo $anforderung[4]; ?></td>
		      <td><? echo $anforderung[5]; ?></td>
		    </tr>
		    <? endforeach; ?>
	   	</table>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>