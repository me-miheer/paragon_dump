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
// Setting up all the parameters 
$mobileNum = mysqli_real_escape_string($mysql,$_REQUEST['mobile']);
$article = mysqli_real_escape_string($mysql,$_REQUEST['article']);

$checkuserquery = mysqli_query($mysql,"SELECT * FROM csv_data_new WHERE qr LIKE '$article' and mobile_number = '$mobileNum'");
$checkuser = mysqli_fetch_array($checkuserquery);
//check weather email address already exists or not
if(empty($checkuser['id'])){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'Already deleted',
    );
    echo json_encode($responce);
    exit;
}

$createuserquery = "DELETE from csv_data_new where qr LIKE '$article' and mobile_number = '$mobileNum' ";
$runcreateusersquery = mysqli_query($mysql,$createuserquery);
// check if user created or not.
if($runcreateusersquery){
    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Deleted successfully',
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
