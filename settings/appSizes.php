<?php

require('../connection.php');

$article = mysqli_real_escape_string($mysql, $_REQUEST['key']);

if(empty($article)){
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Size not found'
    );
    echo json_encode($responce);
    exit;
}

// Assuming $mysql is your MySQLi connection
$keyName = mysqli_real_escape_string($mysql, $_REQUEST['key']);

$query = "SELECT * FROM item_sized WHERE KEYNAME = '$keyName'";

// Execute the query
$result = mysqli_query($mysql, $query);

$respArr = array();

if($result->num_rows > 0) {
// Fetch data from the result set
while ($data = mysqli_fetch_assoc($result)) {
    $respArr[] = $data['SIZE'];  // Use [] to append elements in PHP arrays
}

    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'gender' => $respArr
    );
    echo json_encode($responce);
    exit;
}else{
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Invalid Size.'
    );
    echo json_encode($responce);
    exit;
}