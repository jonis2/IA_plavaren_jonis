<?php
include('data.php');
if ($con = connect_db()) {
		$query = 'SELECT * FROM calendar WHERE date >= "'.$_GET['date'].'" AND date < "'.$_GET['date'].'" +
    INTERVAL 1 DAY';
		$result = $con->query($query); 
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				 echo $row['date']."&".$row['open']."&".$row['close']."&".$row['lane_capacity']."&".$row['empty_lanes'];
			} 
    } else {
     echo "er";  // empty result
    }
	  	$con->close();  
} else { 
     echo "ce"; //connection error
}
?>