<?php
require('../connection.php');
header("Content-Type: text/html");
if(!isset($_REQUEST['id'])){
    echo 'Invalid Parameters';
    exit;
}
$id = $_REQUEST['id'];
$data = mysqli_fetch_assoc(mysqli_query($mysql,"SELECT * FROM location where id = '$id' limit 1"));

$accesskey = $data['accesskey'];
$locationName = $data['location'];

$query = "DELETE FROM location where accesskey = '$accesskey'";
$runquery = mysqli_query($mysql,$query);
if($runquery){
    $query = "DELETE FROM csv_data where server_key = '$accesskey'";
    $runquery = mysqli_query($mysql,$query);
    if($runquery){
        echo 'true';
    }else{
        echo 'false';
    }
}else{
    echo 'false';
}
?>