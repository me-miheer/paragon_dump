<?php
require('../connection.php');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(403);
    $response = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Forbidden, request method should be "POST" only.'
    );
    echo json_encode($response);
    exit;
}

// Check if access token exists
if (empty($_SERVER['HTTP_ACCESSTOKEN'])) {
    http_response_code(403);
    $response = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Forbidden, access key not found'
    );
    echo json_encode($response);
    exit;
}

$accessToken = mysqli_real_escape_string($mysql, $_SERVER['HTTP_ACCESSTOKEN']);
$accessKeyQuery = mysqli_query($mysql, "SELECT * FROM app_settings WHERE app_access_key = '$accessToken'");
$accessKey = mysqli_fetch_array($accessKeyQuery);

// Check if access key is valid
if (empty($accessKey['app_access_key'])) {
    http_response_code(403);
    $response = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Invalid Access Key'
    );
    echo json_encode($response);
    exit;
}

// Check if required parameters are provided
$requiredFields = ['name', 'quantity', 'qr', 'shop', 'mobile', 'type', 'town', 'consumer', 'consumerSize', 'serverkey', 'server', 'dealer'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        $response = array(
            'status' => 'false',
            'response_code' => '400',
            'task_status' => 'false',
            'message' => 'Invalid Parameters'
        );
        echo json_encode($response);
        exit;
    }
}

// Validate quantity
if (!preg_match('/^[0-9]+$/', $_POST['quantity'])) {
    http_response_code(400);
    $response = array(
        'status' => 'false',
        'response_code' => '400',
        'task_status' => 'false',
        'message' => 'Quantity must be a number.'
    );
    echo json_encode($response);
    exit;
}

// Sanitize input
$params = [
    'name' => $_POST['name'],
    'qr' => $_POST['qr'],
    'quantity' => $_POST['quantity'],
    'dealer' => $_POST['dealer'],
    'shop' => $_POST['shop'],
    'mobile' => $_POST['mobile'],
    'type' => $_POST['type'],
    'town' => $_POST['town'],
    'consumer' => $_POST['consumer'],
    'consumerSize' => $_POST['consumerSize'],
    'serverkey' => $_POST['serverkey'],
    'server' => $_POST['server']
];

foreach ($params as &$param) {
    $param = mysqli_real_escape_string($mysql, trim($param));
}

// Validate mobile number
if (strlen($params['mobile']) !== 10) {
    http_response_code(400);
    $response = array(
        'status' => 'false',
        'response_code' => '400',
        'task_status' => 'false',
        'message' => 'Invalid mobile number. It should be 10 digits long.'
    );
    echo json_encode($response);
    exit;
}

$time = date('d-m-Y h:i A', time());
$actual_date = date('Y-m-d', time());

$insertQuery = "INSERT INTO csv_data (qr, name, quantity, dealer, shop_name, mobile_number, type, town, consumer, consumer_size, server_key, server, time, actual_date) 
VALUES ('{$params['qr']}', '{$params['name']}', '{$params['quantity']}', '{$params['dealer']}', '{$params['shop']}', '{$params['mobile']}', '{$params['type']}', '{$params['town']}', '{$params['consumer']}', '{$params['consumerSize']}', '{$params['serverkey']}', '{$params['server']}', '$time', '$actual_date')";

if (mysqli_query($mysql, $insertQuery)) {
    http_response_code(200);
    $response = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Data has been inserted successfully'
    );
    echo json_encode($response);
} else {
    error_log('SQL Error: ' . mysqli_error($mysql));
    http_response_code(503);
    $response = array(
        'status' => 'false',
        'response_code' => '503',
        'task_status' => 'false',
        'message' => 'Service unavailable'
    );
    echo json_encode($response);
}
exit;
?>