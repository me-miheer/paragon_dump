<?php

require('../connection.php');

$article = mysqli_real_escape_string($mysql, $_REQUEST['article']);
$mobileNum = mysqli_real_escape_string($mysql, $_REQUEST['mobile']);

if(empty($article)){
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Article not found'
    );
    echo json_encode($responce);
    exit;
}

$query = "SELECT scheme FROM dumpv2 where article = '$article' order by id desc";

// Execute the query
$result = mysqli_query($mysql, $query);

$query2 = "SELECT qr FROM csv_data_new where qr = '$article' and mobile_number = '$mobileNum' limit 1";

// Execute the query
$result2 = mysqli_query($mysql, $query2);


$respArr = null;

if($result->num_rows > 0) {
    // Fetch data from the result set
    while ($data = mysqli_fetch_assoc($result)) {
        $respArr = $data['scheme'];  // Use [] to append elements in PHP arrays
    }

    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '200',
        'task_status' => 'true',
        'article' => $article,
        'scheme' => $respArr,
        'editable' => !empty($result2->fetch_assoc()['qr']) ? "true" : "false"
    );
    echo json_encode($responce);
    exit;
}else{
    http_response_code(403);
    $responce = array(
        'status' => 'false',
        'response_code' => '403',
        'task_status' => 'false',
        'message' => 'Invalid Article.'
    );
    echo json_encode($responce);
    exit;
}