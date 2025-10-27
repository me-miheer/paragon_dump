<?php
require('../connection.php');
try {
    //check weather request method is post
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
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
    if (empty($_SERVER['HTTP_ACCESSTOKEN'])) {
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
    $key = mysqli_real_escape_string($mysql, $_SERVER['HTTP_ACCESSTOKEN']);
    $accesskeyquery = mysqli_query($mysql, "SELECT * FROM app_settings WHERE app_access_key = '$key'");
    $accesskey = mysqli_fetch_array($accesskeyquery);
    // check weather access key is valid or not
    if (empty($accesskey['app_access_key'])) {
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
    if (empty($_POST['name']) || empty($_POST['qr']) || empty($_POST['shop']) || empty($_POST['mobile']) || empty($_POST['type']) || empty($_POST['town']) || empty($_POST['consumerSizes']) || empty($_POST['dealer'])) {
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
    $name = mysqli_real_escape_string($mysql, trim($_POST['name']));
    $qr = mysqli_real_escape_string($mysql, trim($_POST['qr']));
    $dealer = mysqli_real_escape_string($mysql, trim($_POST['dealer']));
    $shop = mysqli_real_escape_string($mysql, trim($_POST['shop']));
    $mobile = mysqli_real_escape_string($mysql, trim($_POST['mobile']));
    $type = mysqli_real_escape_string($mysql, trim($_POST['type']));
    $town = mysqli_real_escape_string($mysql, trim($_POST['town']));
    $retailType = $_POST['retailType'];
    $time = date('Y-m-d');
    $arrSizeList = [];
    $present_date = date('Y-m-d');

    // Size system
    $consumerSizes = json_decode($_POST['consumerSizes'], true);
    $queryDbSizes = mysqli_query($mysql, "SELECT DISTINCT consumer_size as size FROM csv_data_new WHERE qr = '$qr' and mobile_number  = '$mobile'");
    $dbSizes = array_column(mysqli_fetch_all($queryDbSizes, MYSQLI_ASSOC), 'size');
    $incomingSizes = array_column($consumerSizes, 'size');
    $incomingQuantities = array_column($consumerSizes, 'quantity');
    $quantityInRespectToSize = array_column($consumerSizes, 'quantity', 'size');

    // Final Sizes
    $updateSizes = array_intersect($incomingSizes, $dbSizes);
    $deleteSizes = array_diff($dbSizes, $incomingSizes);
    $insertSizes = array_diff($incomingSizes, $dbSizes);


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

    foreach ($incomingQuantities as $value) {
        if (!preg_match('/^[0-9]+$/', $value)) {
            http_response_code(400);
            $responce = array(
                'status' => 'false',
                'response_code' => '400',
                'task_status' => 'false',
                'message' => 'Quantity could be number only.'
            );
            echo json_encode($responce);
            exit;
        } else if ($value['quantity'] <= 0) {
            http_response_code(400);
            $responce = array(
                'status' => 'false',
                'response_code' => '400',
                'task_status' => 'false',
                'message' => 'Minimum quantity should be 1.'
            );
            echo json_encode($responce);
            exit;
        }
    }

    if ($deleteSizes) {
        foreach ($deleteSizes as $dvalue) {
            $q = "DELETE FROM csv_data_new WHERE qr = '$qr' and mobile_number = '$mobile' and consumer_size = '$dvalue'";
            $r = mysqli_query($mysql, $q);
        }
    }

    if ($updateSizes) {
        foreach ($updateSizes as $uvalue) {
            $qt = $quantityInRespectToSize[$uvalue];
            $q = "UPDATE csv_data_new 
          SET name = '$name', 
              quantity = '$qt', 
              dealer = '$dealer', 
              shop_name = '$shop', 
              mobile_number = '$mobile', 
              type = '$type', 
              town = '$town', 
              retailType = '$retailType',
              acutal_date = '$time' 
          WHERE qr = '$qr' 
          AND mobile_number = '$mobile' 
          AND consumer_size = '$uvalue'";
            $r = mysqli_query($mysql, $q);
        }
    }


    $ti = date('d-m-Y h:i A', time());
    $ai = date('Y-m-d', time());
    $qi = mysqli_query($mysql, "SELECT * FROM csv_data_new WHERE qr = '$qr' and mobile_number  = '$mobile' limit 1");
    $vi = mysqli_fetch_assoc($qi);

    if ($incomingSizes) {
        foreach ($insertSizes as $ivalue) {
            // new quantity for this size
            $newQuantity = $quantityInRespectToSize[$ivalue];

            // build insert using $vi values + overrides
            $createuserquery = "
        INSERT INTO csv_data_new 
            (qr, name, quantity, dealer, shop_name, mobile_number, type, town, consumer, consumer_size, consumer_key, retailType, server_key, server, time, acutal_date)
        VALUES 
            ('{$vi['qr']}',
             '{$vi['name']}',
             '$newQuantity',
             '{$vi['dealer']}',
             '{$vi['shop_name']}',
             '{$vi['mobile_number']}',
             '{$vi['type']}',
             '{$vi['town']}',
             '{$vi['consumer']}',
             '$ivalue',
             '{$vi['consumer_key']}',
             '{$vi['retailType']}',
             '{$vi['server_key']}',
             '{$vi['server']}',
             '{$vi['time']}',
             '{$vi['acutal_date']}'
            )";

            $runcreateusersquery = mysqli_query($mysql, $createuserquery);
        }
    }


    // check if user created or not.
    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'message' => 'Data has been updated successfully'
    );
    echo json_encode($responce);
    exit;
} catch (Exception $th) {
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
