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
if(empty($_POST['name']) || empty($_POST['quantity']) || empty($_POST['qr']) || empty($_POST['shop']) || empty($_POST['mobile']) || empty($_POST['type']) || empty($_POST['town']) || empty($_POST['consumer']) || empty($_POST['consumerSize']) || empty($_POST['serverkey']) || empty($_POST['server']) || empty($_POST['dealer'])){
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

if(!preg_match('/^(['.$custom.'0-9_]*)$/i', $_POST['quantity'])){
    http_response_code(400);
    $responce = array(
        'status' => 'false',
        'response_code' => '400',
        'task_status' => 'false',
        'message' => 'Quantity could be number only.'
    );
    echo json_encode($responce);    
    exit;
}
// Setting up all the parameters 
$name = mysqli_real_escape_string($mysql,trim($_POST['name']));
$qr = mysqli_real_escape_string($mysql,trim($_POST['qr']));
$quantity = mysqli_real_escape_string($mysql,trim($_POST['quantity']));
$dealer = mysqli_real_escape_string($mysql,trim($_POST['dealer']));
$shop = mysqli_real_escape_string($mysql,trim($_POST['shop']));
$mobile = mysqli_real_escape_string($mysql,trim($_POST['mobile']));
$type = mysqli_real_escape_string($mysql,trim($_POST['type']));
$town = mysqli_real_escape_string($mysql,trim($_POST['town']));
$consumer = mysqli_real_escape_string($mysql,trim($_POST['consumer']));
$consumerSize = mysqli_real_escape_string($mysql,trim($_POST['consumerSize']));
$serverkey = mysqli_real_escape_string($mysql,trim($_POST['serverkey']));
$server = mysqli_real_escape_string($mysql,trim($_POST['server']));
$time = date('d-m-Y h:i A',time());
$actual_date = date('Y-m-d',time());

// Check Mobile validation validation
if (strlen($mobile) !== 10) {
    http_response_code(400);
    $responce = array(
        'status' => 'false',
        'response_code' => '400',
        'task_status' => 'false',
        'message' => 'Invalid mobile number'
    );
    echo json_encode($responce);    
    exit;
}

$createuserquery = "INSERT INTO csv_data (qr,name,quantity,dealer,shop_name,mobile_number,type,town,consumer,consumer_size,server_key,server,time,acutal_date) VALUES ('$qr','$name','$quantity','$dealer','$shop','$mobile','$type','$town','$consumer','$consumerSize','$serverkey','$server','$time','$actual_date')";
$runcreateusersquery = mysqli_query($mysql,$createuserquery);
// check if user created or not.
if($runcreateusersquery){
    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Data has been inserted successfully'
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
