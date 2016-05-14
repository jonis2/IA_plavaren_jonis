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
        $_SESSION['id'] = $row['id'];
        $_SESSION['admin'] = false;
        $_SESSION['groups'] = null;
        get_groups($_SESSION['id']);
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
          <ul class="nav navbar-nav">
          <?php if (isset($_SESSION['login']) && $_SESSION['admin']){ ?>
            <li><a href="admin.php">Správa rozvrhu</a></li>
           <?php }?>
           <?php if (isset($_SESSION['login']) && !$_SESSION['admin'] && $_SESSION['login']){ ?>
            <li><a href="gropu_admin.php">Správa skupín</a></li>
           <?php }?>  
          </ul>
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
      $_SESSION['id'] = "";
      $_SESSION['admin'] = false;
      $_SESSION['groups'] = null;
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
function get_groups( $id){
	if ($con = connect_db()) {
		$query = 'SELECT * FROM groups WHERE owner="'.$id.'"'; 
		$result = $con->query($query); 
    $_SESSION['groups'] = null;
		if ($result->num_rows > 0) {
       $res = [];
       $i = 0;
			while ($row = $result->fetch_assoc()) {
        $res[$i]['name'] =  $row['name'];
        $res[$i]['id'] =  $row['id'];
        $res[$i]['public'] =  $row['public'];
        $i  += 1;
			}
       $_SESSION['groups'] =  $res;
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
function delte_group(){
	if ($con = connect_db()) {
		$query = 'DELETE FROM groups WHERE id="'.$_GET['del'].'"'; 
		$result = $con->query($query); 
		if ($result) {
          $query = 'DELETE FROM group_aplications WHERE group="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
          
          $query = 'DELETE FROM group_invitations WHERE group="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
          
          $query = 'DELETE FROM group_mebership WHERE group="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
          
          $query = 'SELECT * FROM group_reservation WHERE group="'.$_GET['del'].'"'; 
        	$result = $con->query($query); 
        	if ($result->num_rows > 0) {
        			while ($row = $result->fetch_assoc()) {
                $query = 'DELETE FROM user_reservation WHERE reservation="'.$row['id'].'"'; 
		            $res = $con->query($query);
        			}
          }
          $query = 'DELETE FROM group_reservation WHERE group="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
        
			}
      $con->close();
  } else { 
     $con->close();
      return false;
  }
} 

function form_add_group(){
?>
<div class="modal fade" id="add_group-modal" tabindex="-1" role="dialog" aria-labelledby="Pridať skupinu" aria-hidden="true" style="display: none;">
    	  <div class="modal-dialog">
				<div class="loginmodal-container">
					<h1>Zadajte údaje skupiny </h1><br>
				  <form id="aform">
					<input id="name" type="text" name="name" placeholder="Názov skupiny">
          <label class="control-label" for="public">Verejná    </label>
					<input type="checkbox" name="public" value="1" >
					<input type="submit" name="add_group" class="login loginmodal-submit" value="Pridať skupinu">
				  </form>
				</div>
			</div>
	</div>
  <a href="#" data-toggle="modal" data-target="#add_group-modal"> <span class="glyphicon glyphicon-plus"></span></a> 
<?php
}
function is_group_name($name){
  if ($con = connect_db()) {
		$query = 'SELECT * FROM groups WHERE name="'.$name.'"'; 
		$result = $con->query($query); 
		if ($result->num_rows > 0) {
          return true;
			} else {
        return false;
      }
      $con->close();
  } else { 
     $con->close();
      return false;
  }
}
function add_group(){
  if (isset($_GET['add_group']) && isset($_GET['name']) && $_GET['name'] != ""){
     $name = test_input($_GET['name']);
     $public = test_input($_GET['public']);
     if ($public != "1"){
      $public = "0";
     }
     if ($name == $_GET['name'] && !is_group_name($name)){
        if ($con = connect_db()) {
    		$query = 'INSERT INTO groups SET name="'.$name.'",public="'.$public.'",owner="'.$_SESSION['id'].'"';
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
     } else {
     ?>
        <div class="alert alert-danger">
          <strong>Skupina s daním menom už existuje</strong>
        </div>
      <?php
     }
    }
}
function group_managment(){
  echo "<h2>Skupina ".$_SESSION['groups'][$_GET['id']]['name']."</h2>";
}