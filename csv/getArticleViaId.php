<?php
require('../connection.php');
$dID = $_REQUEST['dID'];
$query = "SELECT * FROM csv_data where id = '$dID'";
$exce = mysqli_query($mysql, $query);
$data = mysqli_fetch_object($exce);
echo json_encode($data);