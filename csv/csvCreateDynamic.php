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
if(empty($_POST['name']) || empty($_POST['qr']) || empty($_POST['shop']) || empty($_POST['mobile']) || empty($_POST['type']) || empty($_POST['town']) || empty($_POST['consumer']) || empty($_POST['consumerSizes']) || empty($_POST['serverkey']) || empty($_POST['server']) || empty($_POST['dealer'])){
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
$name = mysqli_real_escape_string($mysql,trim($_POST['name']));
$qr = mysqli_real_escape_string($mysql,trim($_POST['qr']));
$dealer = mysqli_real_escape_string($mysql,trim($_POST['dealer']));
$shop = mysqli_real_escape_string($mysql,trim($_POST['shop']));
$mobile = mysqli_real_escape_string($mysql,trim($_POST['mobile']));
$type = mysqli_real_escape_string($mysql,trim($_POST['type']));
$town = mysqli_real_escape_string($mysql,trim($_POST['town']));
$consumer = mysqli_real_escape_string($mysql,trim($_POST['consumer']));
$consumerSizes = $_POST['consumerSizes'];
$consumer_key  = 0;
if(str_contains(strtolower($consumer),'slicker')){
$consumer_key = 4;
}elseif(str_contains(strtolower($consumer),'vertex')){
$consumer_key = 2;
}elseif(str_contains(strtolower($consumer),'solea')){
$consumer_key = 1;
}elseif(str_contains(strtolower($consumer),'toes')){
$consumer_key = 3;
}else{
$consumer_key = 0;
}
$retailType = $_POST['retailType'];
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

foreach(json_decode($consumerSizes, true) as $key => $value) {
    if(!preg_match('/^[0-9]+$/', $value['quantity'])){
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
    $quantity = $value['quantity'];
    $consumerSize = $value['size'];
    $createuserquery = "INSERT INTO csv_data_new (qr,name,quantity,dealer,shop_name,mobile_number,type,town,consumer,consumer_size,consumer_key,retailType,server_key,server,time,acutal_date) VALUES ('$qr','$name','$quantity','$dealer','$shop','$mobile','$type','$town','$consumer','$consumerSize','$consumer_key','$retailType','$serverkey','$server','$time','$actual_date')";
    $runcreateusersquery = mysqli_query($mysql,$createuserquery);
}


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
