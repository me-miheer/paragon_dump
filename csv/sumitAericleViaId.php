<?php
require('../connection.php');
$id = $_REQUEST['id'];
$query = "UPDATE csv_data SET ";
$outputArray = array();
foreach ($_REQUEST as $key => $value) {
    if($key != "id"){
        $outputArray[] = $key . " = '" . $value. "'";
    }
}
$query .= implode(", ", $outputArray);
$query .= " where id = '$id'";
$exec = mysqli_query($mysql, $query);
echo json_encode($exec);