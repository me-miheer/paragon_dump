<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow methods
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type"); // Allow headers

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require('../connection.php');

if (!empty($_REQUEST['key'])) {
    // Assuming $mysql is your MySQLi connection
    $keyName = mysqli_real_escape_string($mysql, $_REQUEST['key']);
    
    $query = "SELECT * FROM item_sized WHERE KEYNAME = '$keyName'";

    // Execute the query
    $result = mysqli_query($mysql, $query);

    $respArr = array();
    $respArr[] = ['data' => 'SELECT ANY'];  // Use [] to append elements in PHP arrays

    // Fetch data from the result set
    while ($data = mysqli_fetch_assoc($result)) {
        $respArr[] = ['data' => $data['SIZE']];  // Use [] to append elements in PHP arrays
    }

    // Encode the response array to JSON and output it
    echo json_encode($respArr);
    exit;
} else {
    
    $query = "SELECT distinct(KEYNAME) FROM item_sized";

    // Execute the query
    $result = mysqli_query($mysql, $query);

    $respArr = array();
    $respArr[] = ['data' => 'SELECT ANY'];  // Use [] to append elements in PHP arrays

    // Fetch data from the result set
    while ($data = mysqli_fetch_assoc($result)) {
        $respArr[] = ['data' => $data['KEYNAME']];  // Use [] to append elements in PHP arrays
    }

    // Encode the response array to JSON and output it
    echo json_encode($respArr);
    exit;
}

?>
