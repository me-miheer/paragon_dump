<?php

require('../connection.php');

$article = mysqli_real_escape_string($mysql, $_REQUEST['key'] ?? null);

$keyName = mysqli_real_escape_string($mysql, $_REQUEST['key']);

$query = "SELECT * FROM csv_data_new WHERE qr LIKE '$keyName'";

$result = mysqli_query($mysql, $query);


$respArr = array();
$articleData = "";
$typeData = "";

if($result->num_rows > 0) {
// Fetch data from the result set
while ($data = mysqli_fetch_assoc(result: $result)) {
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
        )
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
