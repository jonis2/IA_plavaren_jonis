<?php
session_start();
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
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
		$query = 'SELECT * FROM users WHERE user_name="'.$email.'" AND passwd="'.$pass.'"'; 
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
function insert_user($name,$email,$pwd){
  if ($con = connect_db()) {
		$query = 'INSERT INTO users SET user_name="'.$name.'",email="'.$email.'",passwd="'.$pwd.'"' ;
		$result = $con->query($query); 
		if ($result) {
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
function insert_date($date,$open,$close,$lanes){
  if ($con = connect_db()) {
		$query = 'INSERT INTO calendar SET date="'.$date.'",open="'.$open.'",close="'.$close.'",lane_capacity="'.$lanes.'",empty_lanes="'.$lanes.'"';
		$result = $con->query($query); 
		if ($result) {
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
administration();
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
				  <form id="lform">
					<input id="user" type="text" name="user" placeholder="Prihlasovacie meno">
					<input id="pass"type="password" name="pass" placeholder="Heslo">
					<input type="submit" name="login" class="login loginmodal-submit" value="Prihláisť sa">
				  </form>
					
				  <div class="login-help">
					<a href="login.php">Zaregistrovať sa</a>
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
           <?php if (isset($_SESSION['login']) && $_SESSION['admin']){ ?>
          <ul class="nav navbar-nav">
            <li><a href="admin.php">Správa rozvrhu</a></li>
          </ul>
          <?php }?> 
          <ul class="nav navbar-nav navbar-right">
            <?php if (!isset($_SESSION['login'])){ ?>
              <li ><a href="#" data-toggle="modal" data-target="#login-modal"> <span class="glyphicon glyphicon-log-in"></span>Prihásenie</a> </li>
            <?php } else { if (!$_SESSION['login']) { ?>
              <li ><a href="#" data-toggle="modal" data-target="#login-modal"> <span class="glyphicon glyphicon-log-in"></span>Prihásenie</a> </li>
            <?php } else {  ?>
              <li ><a href="index.php?logout=true" > <span class="glyphicon glyphicon-log-in"></span>Odhlásiť sa (<?php echo $_SESSION['user']?>)</a> </li>
            <?php } }?>  
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
  if (isset($_GET['user']) && isset($_GET['pass'])){ 
       get_user($_GET['user'],$_GET['pass']);
  }
}
function logout(){
  if (isset($_SESSION['login'])){
      $_SESSION['login'] = false;
      $_SESSION['user'] = "";
	    $_SESSION['meno'] = "";
      $_SESSION['admin'] = false;
  }
}
function register(){
   $name = test_input($_GET['name']);
   $email = test_input($_GET['email']);
   $pwd = test_input($_GET['pwd']);
   $rpwd = test_input($_GET['rpwd']);
   $_SESSION['register'] = false;
   if ($name == $_GET['name'] && $email == $_GET['email'] && $pwd == $_GET['pwd'] && $rpwd == $_GET['rpwd'] && $pwd == $rpwd){
      if (insert_user($name,$email,$pwd)) {
        $_SESSION['register'] = true;
      }
   }
}
function administration(){ 
  if (isset($_GET['login'])){
    login();
  }
  if (isset($_GET['logout'])){
    logout();
  }
  if (isset($_GET['register'])){
    register();
  }
} 
function try_insert_date($date,$open,$close,$lanes){
  insert_date(test_input($date),test_input($open),test_input($close),test_input($lanes));
} 

