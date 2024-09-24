<?php
require('../connection.php');
$id = $_REQUEST['id'];
$query = "DELETE FROM csv_data WHERE id = '$id'";
$exec = mysqli_query($mysql, $query);
echo json_encode($exec);