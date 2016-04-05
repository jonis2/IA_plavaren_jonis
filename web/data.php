<?php
error_reporting(0);
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

