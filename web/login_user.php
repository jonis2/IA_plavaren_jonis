<?php
include('data.php');
try {
  if (isset($_GET['data'])){
    $data = explode( "|" , $_GET['data'] );
    if (get_user( $data[0],$data[1])) {
      echo "1";
    } else {
      echo  $data[0]." & ". $data[1];
    }
  } else  {
      echo "nd";
  }
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage();
}
?>