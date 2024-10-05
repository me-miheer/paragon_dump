<?php
require('../connection.php');
//check weather request method is post
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Forbidden, request method should be "POST" only.'
    );
    echo json_encode($responce);
    exit;
}
// Check weather access key exists or not
if(empty($_SERVER['HTTP_ACCESSTOKEN'])){
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Forbidden, access key not found'
    );
    echo json_encode($responce);
    exit;
}
$key = mysqli_real_escape_string($mysql,$_SERVER['HTTP_ACCESSTOKEN']);
$accesskeyquery = mysqli_query($mysql,"SELECT * FROM app_settings WHERE app_access_key = '$key'");
$accesskey = mysqli_fetch_array($accesskeyquery);
// check weather access key is valid or not
if(empty($accesskey['app_access_key'])){
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Invalid Access Key'
    );
    echo json_encode($responce);
    exit;
}

//check weather reqquired parameters are there or not
if(empty($_POST['id'] || $_POST['quantity'] || $_POST['type'] || $_POST['consumer_size'])){
    http_response_code(400);
    $responce = array(
        'status' => 'false',
        'response_code' => '400',
        'task_status' => 'false',
        'message' => 'Invalid Parameters'
    );
    echo json_encode($responce);    
    exit;
}
// Setting up all the parameters 

$id = mysqli_real_escape_string($mysql,trim($_POST['id']));
$quantity = mysqli_real_escape_string($mysql,trim($_POST['quantity']));
$type = mysqli_real_escape_string($mysql,trim($_POST['type']));
$consumer_size = mysqli_real_escape_string($mysql,trim($_POST['consumer_size']));

$checkuserquery = mysqli_query($mysql,"SELECT * FROM csv_data WHERE id = '$id'");
$checkuser = mysqli_fetch_array($checkuserquery);
//check weather email address already exists or not
if(empty($checkuser['id'])){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => "Article does'nt exists.",
    );
    echo json_encode($responce);
    exit;
}

$createuserquery = "UPDATE csv_data SET quantity = '$quantity', type = '$type', consumer_size = '$consumer_size' where id = $id";
$runcreateusersquery = mysqli_query($mysql,$createuserquery);
// check if user created or not.
if($runcreateusersquery){
    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Updated successfully!',
    );
    echo json_encode($responce);
    exit;
}else{
    http_response_code(503);
    $responce = array(
        'status' => 'false',
        'response_code' => '503',
        'task_status' => 'false',
        'message' => 'Service unavailable'
    );
    echo json_encode($responce);
    exit;
}
