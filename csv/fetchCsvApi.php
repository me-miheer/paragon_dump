<?php
require('../connection.php');

header('Content-Type: application/json; charset=utf-8');

// Set present date
$present_date = date('Y-m-d');

// Define default values for pagination
$offset = 0;
$limit = 10;

// Prepare response structure
$response = [
    'status' => 400,
    'message' => 'Invalid request',
    'data' => [],
    'total_quantity' => 0,
    'total_set_quantity' => 0,
    'total_pair_quantity' => 0,
    'total_carton_quantity' => 0,
];

// Check if required GET parameters are set
if (isset($_REQUEST['serverkey']) && isset($_REQUEST['offset']) && isset($_REQUEST['limit']) && isset($_REQUEST['mobile'])) {
    $serverkey = mysqli_real_escape_string($mysql, trim($_REQUEST['serverkey']));
    $offset = (int) mysqli_real_escape_string($mysql, trim($_REQUEST['offset']));
    $limit = (int) mysqli_real_escape_string($mysql, trim($_REQUEST['limit']));
    $mobile = mysqli_real_escape_string($mysql, trim($_REQUEST['mobile']));

    // First query to calculate total quantities without pagination
    $totalQuantityQuery = "SELECT 
                            SUM(quantity) as total_quantity,
                            SUM(CASE WHEN type = 'Set' THEN quantity ELSE 0 END) as total_set_quantity,
                            SUM(CASE WHEN type = 'Pair' THEN quantity ELSE 0 END) as total_pair_quantity,
                            SUM(CASE WHEN type = 'Carton' THEN quantity ELSE 0 END) as total_carton_quantity
                           FROM csv_data 
                           WHERE server_key = '$serverkey' 
                           AND mobile_number = '$mobile' 
                           AND acutal_date = '$present_date'";
    
    $totalQuantityResult = mysqli_query($mysql, $totalQuantityQuery);
    
    if ($totalQuantityResult) {
        $totals = mysqli_fetch_assoc($totalQuantityResult);
        $response['total_quantity'] = $totals['total_quantity'] ?? 0;
        $response['total_set_quantity'] = $totals['total_set_quantity'] ?? 0;
        $response['total_pair_quantity'] = $totals['total_pair_quantity'] ?? 0;
        $response['total_carton_quantity'] = $totals['total_carton_quantity'] ?? 0;
    }

    // Second query to get paginated data
    $paginatedQuery = "SELECT * FROM csv_data 
                       WHERE server_key = '$serverkey' 
                       AND mobile_number = '$mobile' 
                       AND acutal_date = '$present_date' 
                       ORDER BY id DESC 
                       LIMIT $offset, $limit";
    
    $paginatedResult = mysqli_query($mysql, $paginatedQuery);

    // Check if query execution was successful
    if ($paginatedResult) {
        $datajson = [];

        while ($data = mysqli_fetch_assoc($paginatedResult)) {
            // Add data to response array
            $datajson[] = $data;
        }

        // Update response with success and data
        http_response_code(200);
        $response['status'] = 200;
        $response['message'] = 'Data fetched successfully';
        $response['data'] = $datajson;
    } else {
        http_response_code(500);
        $response['status'] = 500;
        $response['message'] = 'Failed to execute query';
    }
} else {
    http_response_code(400);
    $response['status'] = 400;
    $response['message'] = 'Required parameters are missing';
}

// Output the JSON response
echo json_encode($response);
?>
