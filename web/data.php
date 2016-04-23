<?php
function connect_db() {
	$conn = new mysqli('localhost', 'jonis2', '123456','plavaren');
  if (! $conn) {
      echo "err : ".$conn->connect_error();
    return false;
  } else {
    return  $conn;
  }
}
function get_user(){
	if ($con = connect_db()) {
		$query = "SELECT * FROM users"; 
		$result = $con->query($query); 
		if ($result) {
			while ($row = $result->fetch_assoc()) {
				echo '>' . $row['email'];
			}
    }
	  	$con->close();
  } else { 
     echo "Conection error";
  }
}
function hlavicka($nazov) {      // priraba hlavicku
?>
<!DOCTYPE HTML>
<html>
  <head>
  <meta charset="utf-8">
  <title><?php  echo $nazov; ?></title>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

  </head>
    <body>
    <div class="container">
       <div class="container">
        <nav class="navbar navbar-inverse">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php">Plaváreň rozvrh</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
          <li <?php  if ($nazov == "Prihlásenie") {echo 'class="active"';} ?> ><a href="login.php"> <span class="glyphicon glyphicon-log-in"></span> Prihlásenie </a> </li>
        </ul>
        </nav>
      </div>  <!-- CONTAINER -->
      
<?php
}

function pata() {                // priraba paticku
 ?>
      <div class="container">
        <footer class="footer" >
          <hr>
          <p class="lead" > Marián Jonis</p>
          <div  class="lead">Email : <a href="mailto:jonismajo@gmail.com" > jonismajo@gmail.com </a> </div>
        </footer>
      </div>
      </div>    <!-- CONTAINER -->
    </body>
</html>  
<?php      
}

