<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow methods
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type"); // Allow headers

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require('../connection.php');

$article = mysqli_real_escape_string($mysql, $_REQUEST['article']);

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

$query = "SELECT gender FROM dump where article = '$article'";

// Execute the query
$result = mysqli_query($mysql, $query);


$respArr = null;

if($result->num_rows > 0) {
    // Fetch data from the result set
    while ($data = mysqli_fetch_assoc($result)) {
        $respArr = $data['gender'];  // Use [] to append elements in PHP arrays
    }

    http_response_code(200);
    $responce = array(
        'status' => 'true',
        'response_code' => '403',
        'task_status' => 'true',
        'article' => $article,
        'gender' => $respArr
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


// Encode the response array to JSON and output it
echo json_encode($respArr);
exit;