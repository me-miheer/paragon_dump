<?php
require('../connection.php');
header("Content-Type: text/html");
if(empty($_REQUEST['id'])){
    header("Location: sizelist.php");
    exit;
}
$id = $_REQUEST['id'];
$mysqli_delete_query = mysqli_query($mysql, "DELETE FROM item_sized where id = '$id'");
header("Location: sizelist.php");
exit;