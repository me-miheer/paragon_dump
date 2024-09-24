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
if(empty($_POST['username']) || empty($_POST['password'])){
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
$username = mysqli_real_escape_string($mysql,trim($_POST['username']));
$password = mysqli_real_escape_string($mysql,trim($_POST['password']));

if (preg_match('/\b@\b/', $username)) {
    $checkuserquery = mysqli_query($mysql,"SELECT * FROM user WHERE email = '$username'");
}else{
    $checkuserquery = mysqli_query($mysql,"SELECT * FROM user WHERE mobile = '$username'");
}
$checkuser = mysqli_fetch_array($checkuserquery);

//check weather user exists or not
if(empty($checkuser['email'])){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'No users found with this mobile number or email address',
    );
    echo json_encode($responce);
    exit;
}

// check weather password is valid or not
if(!password_verify($password,$checkuser['password'])){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'Wrong password',
    );
    echo json_encode($responce);
    exit;
}

// return user if logged in successfully

    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Account has logged in successfully',
        'name' => $checkuser['name'],
        'email' => $checkuser['email'],
        'mobile' => $checkuser['mobile'],
        'dealer' => $checkuser['dealer'],
        'shop' => $checkuser['shop'],
        'town' => $checkuser['town'],
        'created_at' => date('d/m/Y h:i A',$checkuser['created_at']),
        'user_type' => ($checkuser['user_type'])?$checkuser['user_type']:'null'
    );
    echo json_encode($responce);
    exit;