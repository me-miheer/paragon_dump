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
if(empty($_POST['serverkey'])){
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Invalid Parameters'
    );
    echo json_encode($responce);
    exit;
}
$accesskey = mysqli_real_escape_string($mysql,trim($_POST['serverkey']));
$query = mysqli_query($mysql,"select * from location where accesskey = '$accesskey' limit 1");
$data = mysqli_fetch_assoc($query);
if(!empty($data['location'])){
    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Access key is valid',
        'location' => $data['location'],
        'accesskey' => $data['accesskey']
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