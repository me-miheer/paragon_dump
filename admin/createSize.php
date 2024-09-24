<?php
require('../connection.php');
header("Content-Type: text/html");
if(empty($_REQUEST['KEYNAME']) || empty($_REQUEST['SIZE'])){
    header("Location: sizelist.php");
    exit;
}

$KEYNAME = $_REQUEST['KEYNAME'];
$SIZE = $_REQUEST['SIZE'];

$mysqli_create_query = mysqli_query($mysql, "INSERT INTO item_sized (KEYNAME, SIZE) VALUES ('$KEYNAME','$SIZE')");
header("Location: sizelist.php");
exit;
?>