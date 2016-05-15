<?php
include('data.php');
if ($con = connect_db()) {
		$query = 'SELECT * FROM calendar';
		$result = $con->query($query); 
		if ($result->num_rows > 0) {
      $re = "";
			while ($row = $result->fetch_assoc()) {
        $re .= $row['id'].'&'.$row['date'].'&'.$row['open'].'&'.$row['close'].'&'.$row['empty_lanes'].'|' ;
			} 
      echo  $re;
    } else {
     echo "er";  // empty result
    }
	  	$con->close();  
  }  else { 
     echo "ce"; //connection error
  }
?>