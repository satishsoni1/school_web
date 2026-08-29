<?php
$servername = "localhost";
$username = "u768414476_school";
$password = "School@123";
$dbname = "u768414476_school";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM fee_transcation_clone";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $json  = $row["payload_status"];
    $arr = json_decode($json);
    //var_dump($arr);die;
    $query = "update fee_transcation_clone set order_bank_ref_no='".$arr->Order_Status_Result->reference_no."' where transactionID=".$row["transactionID"];
    $conn->query($query);
  }
} else {
  echo "0 results";
}
$conn->close();
?>