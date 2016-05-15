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
            <li><a href="user_admin.php">Moje skupiny</a></li>
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
          $query = 'DELETE FROM group_aplications WHERE groupp="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
          
          $query = 'DELETE FROM group_invitations WHERE groupp="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
          
          $query = 'DELETE FROM group_mebership WHERE groupp="'.$_GET['del'].'"'; 
		      $result = $con->query($query);
          
          $query = 'SELECT * FROM group_reservation WHERE groupp="'.$_GET['del'].'"'; 
        	$result = $con->query($query); 
        	if ($result->num_rows > 0) {
        			while ($row = $result->fetch_assoc()) {
                $query = 'DELETE FROM user_reservation WHERE reservation="'.$row['id'].'"'; 
		            $res = $con->query($query);
        			}
          }
          $query = 'DELETE FROM group_reservation WHERE groupp="'.$_GET['del'].'"'; 
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
function get_group_reservations($id){
  if ($con = connect_db()) {
		$query = 'SELECT * FROM group_reservation WHERE groupp="'.$id.'"'; 
		$result = $con->query($query);
    $_SESSION['g_res'] = null; 
		if ($result->num_rows > 0) {
           $res = [];
           $i = 0;
          	while ($row = $result->fetch_assoc()) {
                $res[$i]['id'] = $row['id'];
                $res[$i]['date'] = $row['date'];
                $res[$i]['start'] = $row['start'];
                $res[$i]['end'] = $row['end'];
                $res[$i]['lanes'] = $row['lanes'];
                $res[$i]['group'] = $row['groupp'];
                $i += 1;
        		}
            $_SESSION['g_res'] = $res;
			} else {
      }
      $con->close();
  } else { 
     $con->close();
  }
}
function delete_group_reservation($id){
  if ($con = connect_db()) {
		$query = 'DELETE FROM group_reservation WHERE id="'.$id.'"'; 
		$result = $con->query($query); 
		if ($result) {
          $query = 'DELETE FROM user_reservation WHERE reservation="'.$id.'"'; 
		      $res = $con->query($query);
        
			}
      $con->close();
  } else { 
     $con->close();
      return false;
  }
}
function calendar_data(){
if ($con = connect_db()) {
		$query = 'SELECT * FROM calendar';
		$result = $con->query($query); 
		if ($result->num_rows > 0) {
      echo "<script>";
      echo "var data = [];".' ';
			while ($row = $result->fetch_assoc()) {
        echo "data.push([]);";
        echo "data[data.length-1].push(".$row['id'].");";
        echo 'data[data.length-1].push("'.$row['date'].'");';
        echo 'data[data.length-1].push("'.$row['open'].'");';
        echo 'data[data.length-1].push("'.$row['close'].'");';
        echo 'data[data.length-1].push('.$row['empty_lanes'].');';
        echo 'data[data.length-1].push('.$row['lane_capacity'].');';
			} 

      echo "var reservs =[];";

      $query = 'SELECT * FROM group_reservation';
  		$result = $con->query($query); 
  		if ($result->num_rows > 0) {
  			while ($row = $result->fetch_assoc()) {
          echo "reservs.push([]);";
          echo "reservs[reservs.length-1].push(".$row['id'].");";
          echo 'reservs[reservs.length-1].push("'.$row['date'].'");';
          echo 'reservs[reservs.length-1].push("'.$row['start'].'");';
          echo 'reservs[reservs.length-1].push("'.$row['end'].'");';
          echo 'reservs[reservs.length-1].push('.$row['lanes'].');';
  			} 
      }
      echo "</script>";
    }
	  	$con->close();  
  }  else { 
     echo "ce"; //connection error
  }
}
function add_group_reservation($group,$date,$start,$end,$lanes){
      if ($con = connect_db()) {
    		$query = 'INSERT INTO group_reservation SET groupp="'.$group.'",date="'.$date.'",start="'.$start.'",end="'.$end.'",lanes="'.$lanes.'"';
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
function group_res(){
if (isset($_GET['reserve'])){
  if (isset($_GET['date']) && isset($_GET['open']) && isset($_GET['close']) && isset($_GET['lanes']) &&
  $_GET['date'] != "" && $_GET['open'] != "0" && $_GET['close'] != "0" && $_GET['lanes'] != "0"){
      add_group_reservation($_SESSION['group_id'],$_GET['date'],$_GET['open'],$_GET['close'],$_GET['lanes']);
  ?>
      <div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Rezervácia úspešná</strong>
    </div>
  <?php
  } else  {
  ?>
      <div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Rezervácia neplatná</strong>(neúplné vstupné dáta)
    </div>
  <?php
  }  
}
}
function manage_application(){
  if (isset($_GET['ref'])){
      if ($con = connect_db()) {
    		$query = 'DELETE FROM group_aplications WHERE id="'.$_GET['ref'].'"'; 
    		$result = $con->query($query); 
         ?>
          <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Žiadosť zamietnutá</strong>
          </div>
      <?php
        $con->close();
      } else { 
         $con->close();
          return false;
      }
        
  }
  if (isset($_GET['acc'])){
      if ($con = connect_db()) {
    		$query = 'DELETE FROM group_aplications WHERE id="'.$_GET['acc'].'"'; 
    		$result = $con->query($query); 
        
       $query = 'INSERT INTO group_mebership SET groupp="'.$_GET['gid'].'",user="'.$_GET['uid'].'"'; 
    		$result = $con->query($query);  
         ?>
          <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Žiadosť akceptovaná</strong>
          </div>
      <?php
        $con->close();
      } else { 
         $con->close();
          return false;
      }
        
  }
}
function invitations_table($group){
if ($con = connect_db()) {
		$query = 'SELECT * FROM group_aplications WHERE groupp="'.$group.'"'; 
		$result = $con->query($query);
		if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()){
             $query = 'SELECT * FROM users WHERE id="'.$row['user'].'"'; 
		         $res = $con->query($query); 
             if ($res->num_rows > 0) {
             while ($ro = $res->fetch_assoc()){
                echo "<tr>";
                echo  '<td>'.$ro['user_name'].'</td>';
                echo  '<td>'.$ro['email'].'</td>';
                echo '<td><a href="gropu_admin.php?id='.$_GET['id'].'&acc='.$row['id'].'&uid='.$ro['id'].'&gid='.$group.'"><span class="glyphicon glyphicon-ok"></span></a></td>';
                echo '<td><a href="gropu_admin.php?id='.$_GET['id'].'&ref='.$row['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
                echo "</tr>";
            }
            } 
			}
      }
      $con->close();
  } else { 
     $con->close();
  }

}
function add_request(){
 if (isset($_GET['invite'])){
  if (isset($_GET['u_name']) &&  $_GET['u_name'] != ""){
    if ($con = connect_db()) {
       	$query = 'SELECT * FROM users WHERE user_name="'.$_GET['u_name'].'"'; 
		    $result = $con->query($query); 
		    if ($result->num_rows > 0) {
			  while ($row = $result->fetch_assoc()) {
              $query = 'INSERT INTO group_invitations SET groupp="'.$_SESSION['group_id'].'",user="'.$row['id'].'"';
		          $res = $con->query($query);
               ?>
              <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Pouzvánka odoslaná</strong>
              </div>
           <?php
        }
			 } else {
            ?>
            <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Používateľ s danám mneom nexistuje</strong>
            </div>
           <?php
      }
      $con->close();
    }
   } else {
      ?>
          <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Nezadali ste meno</strong>
          </div>
      <?php
   }
  
  }
}
function group_managment(){
  echo "<h2>Skupina ".$_SESSION['groups'][$_GET['id']]['name']."</h2>";
    if (isset($_GET['del_res'])){
      delete_group_reservation($_GET['del_res']);
    }
    group_res();
    get_group_reservations( $_SESSION['groups'][$_GET['id']]['id']);
    echo calendar_data();
    manage_application();
  ?>
  <h3>Rezervácie</h3>
  <div class="table-responsive" id="test">
      <table class="table">
       <thead>
        <tr>
          <th>Dátum</th>
          <th>Začiatok</th>
          <th>Koniec</th>
          <th>Počet dráh</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
          if (isset($_SESSION['g_res']) && $_SESSION['g_res'] != null){
            for ($i = 0;$i <   sizeof($_SESSION['g_res']);$i ++){
              echo "<tr>";
              echo  '<td>'.$_SESSION['g_res'][$i]['date'].'</td>';
              echo  '<td>'.$_SESSION['g_res'][$i]['start'].'</td>';
              echo  '<td>'.$_SESSION['g_res'][$i]['end'].'</td>';
              echo  '<td>'.$_SESSION['g_res'][$i]['lanes'].'</td>';
              echo '<td><a href="gropu_admin.php?id='.$_GET['id'].'&del_res='.$_SESSION['g_res'][$i]['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
              echo "</tr>";
            }
          }   
        ?>
        </tbody>
      </table>
      </div>
      <script>
      function selectt() {
           lanes = 0;
           for(i=0;i<data.length;i++){
              if ($("#date").val() == data[i][1]){
                $("#lanes").empty();
                $("#close").empty();
                start = parseInt($('#open').val()[0]+$('#open').val()[1]);
                endd = parseInt(data[i][3][0]+data[i][3][1]); 
                start += 1;
                lanes =  data[i][4];
                while (start < endd){
                 temp = "";
                 if (start > 9){
                  temp += start.toString();
                 } else {
                  temp += '0';
                  temp += start.toString();
                 }
                 temp += data[i][2].substring(2);
                 $("#close").append('<option value="'+temp+'">'+temp+'</option>');
                  start++;
                }
              }
            }
            for (i = 0; i<reservs.length;i++){
                if ($("#date").val() == reservs[i][1] && $("#open").val() == reservs[i][2] ){
                 lanes -=  reservs[i][4];
                }
            }
            for (i = 1;i <= lanes;i++){
             $("#lanes").append('<option value="'+i.toString()+'">'+i.toString()+'</option>');
            }
           
      }
      </script>
     <div class="modal fade" id="add_res-modal" tabindex="-10" role="dialog" aria-labelledby="Pridať skupinu" aria-hidden="true" style="display: none;">
    	  <div class="modal-dialog">
				<div class="loginmodal-container">
					<h1>Zvolte rezervačné údaje</h1><br>
				  <form id="aform">
          <div class="container">
                  <div id="datepicker"></div>
          </div>
					<input type="input" class="form-control" name="date" id="date" value="" placeholder="Zvoľte dátum v kalendáry" readonly="readonly">
          <label  for="open">Začiatok:</label>
          <select class="form-control" name="open" id="open"  value = "0" onchange="selectt();"">
          </select>
          <label for="close">Koniec:</label>
          <select class="form-control" name="close" id="close" value = "0">
          </select>
          <label for="close">Počet dráh:</label>
          <select class="form-control" name="lanes" id="lanes" value = "0">
          </select>
					<input type="submit" name="reserve" class="login loginmodal-submit" value="Rezervovať">
          <?php $_SESSION['group_id'] = $_SESSION['groups'][$_GET['id']]['id']; ?>
				  </form>
				</div>
			</div>
	   </div> 
    <a href="#" data-toggle="modal" data-target="#add_res-modal"> <span class="glyphicon glyphicon-plus"></span></a>
    
  <h3>Žiadosti o priatie do skupiny</h3>
  <div class="table-responsive" >
      <table class="table">
       <thead>
        <tr>
          <th>Meno</th>
          <th>email</th>
          <th></th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        invitations_table($_SESSION['groups'][$_GET['id']]['id']);  
        ?>
        </tbody>
      </table>
      </div>
      <h4>Pozvať použivateľa</h4>
      <form class="form-inline" >
          <label class="control-label" for="u_name">Meno:</label>
          <input type="input" class="form-control" name="u_name" id="u_name" placeholder="Zadajte meno">
          <input type="submit" name="invite" class="btn-primary" value="Pozvať">
      </form>
    <script>
     $(function() {
      function available(date) {
          d =  $.datepicker.formatDate( 'yy-mm-dd', date);
          for(i=0;i< data.length ;i++){
            if (d == data[i][1]){
              return [true, "","Available"];
            }
          }
          return [false,"","unAvailable"];
      } 
      $( "#datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0,
        beforeShowDay: available,
        onSelect: function(dateText, inst) { 
           $("#date" ).val(dateText);
           lanes = 0;
           for(i=0;i<data.length;i++){
              if (dateText == data[i][1]){
                $("#open").empty();
                $("#close").empty();
                start = parseInt(data[i][2][0]+data[i][2][1]);
                endd = parseInt(data[i][3][0]+data[i][3][1]); 
                while (start < endd){
                 temp = "";
                 if (start > 9){
                  temp += start.toString();
                 } else {
                  temp += '0';
                  temp += start.toString();
                 }
                 temp += data[i][2].substring(2);
                 $("#open").append('<option value="'+temp+'">'+temp+'</option>');
                  start++;
                }   
              }
           }
        }
      });
    });
    </script>
    <?php
}
function my_groups_and_res(){
 if ($con = connect_db()) {
		$query = 'SELECT * FROM group_mebership WHERE user="'.$_SESSION['id'].'"'; 
		$result = $con->query($query);
    $_SESSION['m_groups'] = [];
    $_SESSION['m_res'] = null;
    $res = [];
    $ress = []; 
		if ($result->num_rows > 0) {
           $i = 0;
           $j = 0;
          	while ($row = $result->fetch_assoc()) {
                $query = 'SELECT * FROM groups WHERE id="'.$row['groupp'].'"'; 
		            $r = $con->query($query);
                if ($r->num_rows > 0) {
          	     while ($roww = $r->fetch_assoc()) {
                  $_SESSION['m_groups'][$i]['id'] = $roww['id'];
                  $_SESSION['m_groups'][$i]['idm'] = $row['id'];
                  $_SESSION['m_groups'][$i]['name'] = $roww['name'];
                   $i += 1;
                 }
                }
                $query = 'SELECT * FROM group_reservation WHERE groupp="'.$row['groupp'].'"'; 
  		             $rr = $con->query($query);
                   if ($rr->num_rows > 0) {
            	       while ($rowww = $rr->fetch_assoc()) {
                      $ress[$j]['id'] = $rowww['id'];
                      $ress[$j]['date'] = $rowww['date'];
                      $ress[$j]['start'] = $rowww['start'];
                      $ress[$j]['end'] = $rowww['end'];
                      $ress[$j]['lanes'] = $rowww['lanes'];
                      $ress[$j]['gid'] = $rowww['groupp'];
                      $j += 1;
                     }
                    }
        		}
            $_SESSION['m_res'] = $ress; 
			}
      $con->close();
  } else { 
     $con->close();
  }
}
function get_user_res($date,$rid){
 if ($con = connect_db()) {
		$query = 'SELECT * FROM calendar WHERE date="'.$date.'"'; 
		$result = $con->query($query);
    $_SESSION['l_cap'] = 0;
    $_SESSION['rrs'] = 0;
    $_SESSION['I_R'] = false;
		if ($result->num_rows > 0) {
          	while ($row = $result->fetch_assoc()) {
              $_SESSION['l_cap'] = $row['lane_capacity'];
        		}
		}
    $query = 'SELECT * FROM user_reservation WHERE reservation="'.$rid.'"'; 
		$result = $con->query($query);
    if ($result->num_rows > 0) {
          	while ($row = $result->fetch_assoc()) {
              $_SESSION['rrs'] += 1;
        		}
		}
    $query = 'SELECT * FROM user_reservation WHERE reservation="'.$rid.'" AND user="'.$_SESSION['id'].'"'; 
		$result = $con->query($query);
    if ($result->num_rows > 0) {
          	while ($row = $result->fetch_assoc()) {
              $_SESSION['I_R'] = true;
        		}
		}
    $con->close();
  } else { 
     $con->close();
  }  
}
function user_add_del_ress(){
 if (isset($_GET['addr'])){
  if ($con = connect_db()) {
    $query = 'INSERT INTO user_reservation SET reservation="'.$_GET['addr'].'",user="'.$_SESSION['id'].'"';
		$res = $con->query($query);
  }
 }
 if (isset($_GET['delr'])){
 if ($con = connect_db()) {
    $query = 'DELETE FROM user_reservation WHERE reservation="'.$_GET['delr'].'" AND user="'.$_SESSION['id'].'"';
		$res = $con->query($query);
   }
 }
}
function dell_mebership(){

 if (isset($_GET['del'])){
 if ($con = connect_db()) {
    $query = 'DELETE FROM group_mebership WHERE id="'.$_GET['del'].'"';
		$res = $con->query($query);
   }
 }
}
function user_add_del_invit(){
 if (isset($_GET['acci'])){
  if ($con = connect_db()) {
    $query = 'INSERT INTO group_mebership SET groupp="'.$_GET['gid'].'",user="'.$_SESSION['id'].'"';
		$res = $con->query($query);
    
    $query = 'DELETE FROM group_invitations WHERE id="'.$_GET['acci'].'"';
		$res = $con->query($query);
  }
 }
 if (isset($_GET['deli'])){
  if ($con = connect_db()) {
    $query = 'DELETE FROM group_invitations WHERE id="'.$_GET['deli'].'"';
		$res = $con->query($query);
   }
 }
}
function get_my_invitations(){
 if ($con = connect_db()) {
		$query = 'SELECT * FROM group_invitations WHERE user="'.$_SESSION['id'].'"'; 
		$result = $con->query($query);
    $_SESSION['my_in'] = null;
    $i = 0;
		if ($result->num_rows > 0) {
          	while ($row = $result->fetch_assoc()) {
              $_SESSION['my_in'][$i]['id'] = $row['id'];
              $query = 'SELECT * FROM groups WHERE id="'.$row['groupp'].'"'; 
          		$res = $con->query($query);
          		if ($res->num_rows > 0) {
                    	while ($roww = $res->fetch_assoc()) {
                        $_SESSION['my_in'][$i]['name'] = $roww['name'];
                        $_SESSION['my_in'][$i]['gid'] = $roww['id'];
                      }
              }
              $i += 1;
        		}
		}
    $con->close();
  } else { 
     $con->close();
  } 
}
function  my_management(){
  if (isset($_SESSION['login']) && !$_SESSION['admin'] && $_SESSION['login']){
       dell_mebership();
       my_groups_and_res();
      if (isset($_GET['id'])){
        user_add_del_ress();
      ?>
      <h2>Rezervácie skupiny <?php echo $_SESSION['m_groups'][$_GET['id']]['name']; ?></h2>
      <div class="table-responsive">
      <table class="table">
       <thead>
        <tr>
          <th>Dátum</th>
          <th>Začiatok</th>
          <th>Koniec</th>
          <th>Voľné miesto</th>
          <th>Pridať/dodobrať sa</th>
        </tr>
        </thead>
        <tbody>
        <?php
          if (isset($_SESSION['m_res']) && $_SESSION['m_res'] != null){
            for ($i = 0;$i <   sizeof($_SESSION['m_res']);$i ++){
            if ($_SESSION['m_groups'][$_GET['id']]['id'] == $_SESSION['m_res'][$i]['gid']) {
              get_user_res($_SESSION['m_res'][$i]['date'],$_SESSION['m_res'][$i]['id']);
              $count =  intval($_SESSION['m_res'][$i]['lanes'])* $_SESSION['l_cap'] -  $_SESSION['rrs'];
              $class = "success";
              if (isset($_SESSION['I_R']) && $_SESSION['I_R']){
                $class = "info";
              } else {
                if ($count < 1){
                  $class = "daneger";
                }
              }
              echo '<tr class="'.$class.'">';
              echo  '<td>'.$_SESSION['m_res'][$i]['date'].'</td>';
              echo  '<td>'.$_SESSION['m_res'][$i]['start'].'</td>';
              echo  '<td>'.$_SESSION['m_res'][$i]['end'].'</td>';
              echo  '<td>'.strval($count).'</td>';
              if (isset($_SESSION['I_R']) && $_SESSION['I_R']){
                echo '<td><a href="user_admin.php?id='.$_GET['id'].'&delr='.$_SESSION['m_res'][$i]['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
              } else{
                if ($count > 0){
                  echo '<td><a href="user_admin.php?id='.$_GET['id'].'&addr='.$_SESSION['m_res'][$i]['id'].'"><span class="glyphicon glyphicon-plus"></span></a></td>';
                }
              }
              echo "</tr>";
             }
            }
          }    
        ?>
        </tbody>
      </table>
      </div>
      <?php
      } 
      else { 
      user_add_del_invit();
      my_groups_and_res();  
      ?>
      <h2>Moje skupiny</h2>
      <div class="table-responsive">
      <table class="table">
       <thead>
        <tr>
          <th>Názov skupiny</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
          if (isset($_SESSION['m_groups']) && $_SESSION['m_groups'] != null){
            for ($i = 0;$i <   sizeof($_SESSION['m_groups']);$i ++){
              echo "<tr>";
              echo  '<td><a href="user_admin.php?id='.$i.'">'.$_SESSION['m_groups'][$i]['name'].'</a></td>';
              echo '<td><a href="user_admin.php?del='.$_SESSION['m_groups'][$i]['idm'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
              echo "</tr>";
            }
          }    
        ?>
        </tbody>
      </table>
      </div>
      <h2>Pozvánky do skupín</h2>
      <div class="table-responsive">
      <table class="table">
       <thead>
        <tr>
          <th>Názov skupiny</th>
          <th></th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
          get_my_invitations();
          if (isset($_SESSION['my_in']) && $_SESSION['my_in'] != null){
            for ($i = 0;$i <   sizeof($_SESSION['my_in']);$i ++){
              echo "<tr>";
              echo '<td>'.$_SESSION['my_in'][$i]['name'].'</td>';
              echo '<td><a href="user_admin.php?acci='.$_SESSION['my_in'][$i]['id'].'&gid='.$_SESSION['my_in'][$i]['gid'].'"><span class="glyphicon glyphicon-ok"></span></a></td>';
              echo '<td><a href="user_admin.php?deli='.$_SESSION['my_in'][$i]['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
              echo "</tr>";
            }
          }    
        ?>
        </tbody>
      </table>
      </div>
      <?php
        } }
        else {
          echo '<p class="lead">Prístup omietnutý</p>';
        }
}