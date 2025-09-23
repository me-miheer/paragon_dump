<?php

require('../connection.php');

$article = mysqli_real_escape_string($mysql, $_REQUEST['key'] ?? null);
$mobile = mysqli_real_escape_string($mysql, $_REQUEST['mobile'] ?? null);

$keyName = mysqli_real_escape_string($mysql, $_REQUEST['key']);

$query = "SELECT * FROM csv_data_new WHERE qr LIKE '$keyName' and mobile_number = '$mobile' ORDER BY id ASC";

$result = mysqli_query($mysql, $query);

$query2 = "SELECT DISTINCT size FROM dumpv2 WHERE article LIKE '$keyName'";
$result2 = mysqli_query($mysql, $query2);


$respArr = array();
$respArr2 = array();
$articleData = "";
$typeData = "";

if($result->num_rows > 0) {
// Fetch data from the result set
while ($data = mysqli_fetch_assoc($result)) {
    $articleData  = $data["qr"];
    $typeData  = $data["type"];
    $tamp = [];
    if(!in_array($data["consumer_size"], $respArr)){
        $tamp["size"] = $data["consumer_size"];
        $tamp["quantity"] = $data["quantity"];
    }
    if($tamp){
        $respArr[] = $tamp;
    }
}
while ($data2 = mysqli_fetch_assoc($result2)) {
    if(!in_array($data2["size"], $respArr)){
        $respArr2[] = $data2["size"];
    }
}

if(count($respArr) == 0) {
    $respArr[] = 'Default';
}

    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'data' => array(
            "article" => $articleData,
            "type" => $typeData,
            "sizes" => $respArr
        ),
        "size" => $respArr2
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
