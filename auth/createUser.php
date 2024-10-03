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
if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['mobile']) || empty($_POST['shop']) || empty($_POST['town']) || empty($_POST['password'])){
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
// Check Email validation validation
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    $responce = array(
        'status' => 'false',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'Invalid Email address'
    );
    echo json_encode($responce);    
    exit;
}
// Setting up all the parameters 
$name = mysqli_real_escape_string($mysql,trim($_POST['name']));
$email = mysqli_real_escape_string($mysql,trim($_POST['email']));
$mobile = mysqli_real_escape_string($mysql,trim($_POST['mobile']));
$dealer = mysqli_real_escape_string($mysql,trim($_POST['dealer']));
$shop = mysqli_real_escape_string($mysql,trim($_POST['shop']));
$town = mysqli_real_escape_string($mysql,trim($_POST['town']));
$password = trim(password_hash(mysqli_real_escape_string($mysql,$_POST['password']),PASSWORD_DEFAULT));
$actual_date = date('Y-m-d',time());
$created_at = time();

//check weather email address already exists or not
if(strlen($mobile) !== 10){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'Invalid mobile number',
    );
    echo json_encode($responce);
    exit;
}

$checkuserquery = mysqli_query($mysql,"SELECT * FROM user WHERE email = '$email'");
$checkuser = mysqli_fetch_array($checkuserquery);
//check weather email address already exists or not
if(!empty($checkuser['email'])){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'The given email address already exists',
    );
    echo json_encode($responce);
    exit;
}

$checkuserquery = mysqli_query($mysql,"SELECT * FROM user WHERE mobile = '$mobile'");
$checkuser = mysqli_fetch_array($checkuserquery);
//check weather email address already exists or not
if(!empty($checkuser['email'])){
    http_response_code(200); 
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'false',
        'message' => 'The given mobile number already exists',
    );
    echo json_encode($responce);
    exit;
}

$createuserquery = "INSERT INTO user (name,email,mobile,dealer,shop,town,password,actual_date,created_at) VALUES ('$name','$email','$mobile','$dealer','$shop','$town','$password','$actual_date','$created_at')";
$runcreateusersquery = mysqli_query($mysql,$createuserquery);
// check if user created or not.
if($runcreateusersquery){
    $checkuserquery = mysqli_query($mysql,"SELECT * FROM user WHERE email = '$email'");
    $checkuser = mysqli_fetch_assoc($checkuserquery);
    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Account has been created successfully',
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
