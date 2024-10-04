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

    // Query to get paginated data
    $createuserquery = "SELECT * FROM csv_data 
                        WHERE server_key = '$serverkey' 
                        AND mobile_number = '$mobile' 
                        AND acutal_date = '$present_date' 
                        ORDER BY id DESC 
                        LIMIT $offset, $limit";

    $runcreateusersquery = mysqli_query($mysql, $createuserquery);

    // Check if query execution was successful
    if ($runcreateusersquery) {
        $datajson = [];
        $total_quantity = 0;
        $total_set_quantity = 0;
        $total_pair_quantity = 0;
        $total_carton_quantity = 0;

        while ($data = mysqli_fetch_assoc($runcreateusersquery)) {
            // Add data to response array
            $datajson[] = $data;

            // Calculate total quantity
            $total_quantity += $data['quantity'];

            // Calculate total based on types
            if ($data['type'] === 'Set') {
                $total_set_quantity += $data['quantity'];
            } elseif ($data['type'] === 'Pair') {
                $total_pair_quantity += $data['quantity'];
            } elseif ($data['type'] === 'Carton') {
                $total_carton_quantity += $data['quantity'];
            }
        }

        // Update response with success and data
        http_response_code(200);
        $response['status'] = 200;
        $response['message'] = 'Data fetched successfully';
        $response['data'] = $datajson;
        $response['total_quantity'] = $total_quantity;
        $response['total_set_quantity'] = $total_set_quantity;
        $response['total_pair_quantity'] = $total_pair_quantity;
        $response['total_carton_quantity'] = $total_carton_quantity;
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
