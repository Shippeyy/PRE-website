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

  <?php
  session_start();
	include 'credentials.php'; //credentials for the db connection

	error_reporting(E_ALL & ~E_NOTICE);

	try {
	    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    }
	catch(PDOException $e)
	    {
	    echo "Database connection failed: " . $e->getMessage();
	    }

	$sth = $conn->prepare('SELECT titel, beschreibung, erstellDatum, p_besitzer, p_empfaenger FROM anforderungen
	WHERE p_besitzer = :user');
	$sth->bindParam(':user', $user = 1);
	$sth->execute();
	$result = $sth->fetchAll();

	?>
	    
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <p class="navbar-text">SimpleFeatures</p>
        <a type="button" class="btn btn-default navbar-btn" href="main.php">Dashboard</a>
        <a type="button" class="btn btn-default navbar-btn" href="login.php">Login</a>
        <a type="button" class="btn btn-default navbar-btn" href="logout.php">Logout</a>
        <a type="button" class="btn btn-default navbar-btn" href="register.php">Register</a>
      </div>
    </nav>

    <div class="container-fluid">

    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>