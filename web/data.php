<?php
session_start();
function connect_db() {
	$conn = new mysqli('localhost', 'jonis2', '123456','plavaren');
  if (! $conn) {
      echo "err : ".$conn->connect_error();
    return false;
  } else {
    return  $conn;
  }
}
function get_user( $email,$pass){
	if ($con = connect_db()) {
		$query = 'SELECT * FROM users WHERE email="'.$email.'" AND passwd="'.$pass.'"'; 
		$result = $con->query($query); 
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
        $_SESSION['login'] = true;
	      $_SESSION['user'] = $row['user_name'];
	      $_SESSION['meno'] = $row['email'];
        $_SESSION['admin'] = false;
        if ($row['user_name'] == "admin"){
          $_SESSION['admin'] = true;
        }
			}
      $con->close();
      return true;
    } else {
      $con->close();
      return false;
    } 
  } else { 
     $con->close();
      return false;
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
  <link href="css/login.css"  rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
  <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    	  <div class="modal-dialog">
				<div class="loginmodal-container">
					<h1>Zadajte prihlasovacie údaje</h1><br>
				  <form>
					<input type="text" name="user" placeholder="Email">
					<input type="password" name="pass" placeholder="Heslo">
					<input type="submit" name="login" class="login loginmodal-submit" value="Prihláisť sa">
				  </form>
					
				  <div class="login-help">
					<a href="#">Zaregistrovať sa</a>
				  </div>
				</div>
			</div>
	</div>

  </head>
    <body>
    <div class="container">
        <nav class="navbar navbar-inverse">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Plaváreň rozvrh</a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <?php if (!isset($_SESSION['login'])){ ?>
              <li ><a href="#" data-toggle="modal" data-target="#login-modal"> <span class="glyphicon glyphicon-log-in"></span>Prihásenie</a> </li>
            <?php } else { ?>
              <li ><a href="#" data-toggle="modal" data-target="#login-modal"> <span class="glyphicon glyphicon-log-in"></span>Odhlásiť sa</a> </li>
            <?php }?>  
          </ul>
        </nav>
      
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
function login(){
 if (get_user( $_POST["user"],$_POST["pass"])){
 }  else {
       session_unset();
	     session_destroy();
 }
}
function administration(){
  if (isset($_POST["login"])){
      login();
  } 
} 

