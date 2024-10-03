<?php
require('../connection.php');

$present_date = date('Y-m-d');

$createuserquery = "SELECT * FROM csv_data where acutal_date = '$present_date' order by id desc limit 10";

if (isset($_REQUEST['serverkey']) && isset($_REQUEST['offset']) && isset($_REQUEST['limit']) && isset($_REQUEST['mobile'])) {
  $serverkey = mysqli_real_escape_string($mysql, trim($_REQUEST['serverkey']));
  $offset = mysqli_real_escape_string($mysql, trim($_REQUEST['offset']));
  $limit = mysqli_real_escape_string($mysql, trim($_REQUEST['limit']));
  $mobile = mysqli_real_escape_string($mysql, trim($_REQUEST['mobile']));
  $createuserquery = "SELECT * FROM csv_data where server_key = '$serverkey' and mobile_number = '$mobile' and acutal_date = '$present_date' order by id desc";
  // $createuserquery = "SELECT * FROM csv_data where server_key = '$serverkey' and mobile_number = '$mobile' and acutal_date = '$present_date' order by id desc limit $offset,$limit";
}


$runcreateusersquery = mysqli_query($mysql, $createuserquery);
// check if user created or not.
if ($runcreateusersquery) {
  $datajson = array();
  $countdata = 0;
  while ($data = mysqli_fetch_assoc($runcreateusersquery)) {
    $datajson[] = $data;
    $countdata++;
  }

  $qtyplusplus = 0;
  $setplusplus = 0;
  $pairplusplus = 0;
  $cartonplusplus = 0;
  foreach ($datajson as $dataa) {
    $response[] = $dataa;
    $qtyplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    if($dataa['type'] == 'Set'){
        $setplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    }
    if($dataa['type'] == 'Pair'){
        $pairplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    }
    if($dataa['type'] == 'Carton'){
        $cartonplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    }
  }

  $datajson2 = array();
  $datajson2[] = array(
    "name" => "Total Quantity",
    "value" => $qtyplusplus
  );
  $datajson2[] = array(
    "name" => "Total Sets",
    "value" => $setplusplus
  );
  $datajson2[] = array(
    "name" => "Total Pairs",
    "value" => $pairplusplus
  );
  $datajson2[] = array(
    "name" => "Total Cartons",
    "value" => $cartonplusplus
  );

  http_response_code(200);
  $responce = array(
    'status' => 'true',
    'response_code' => '200',
    'task_status' => 'true',
    "json_data" => $datajson,
    "json_sum" => $datajson2
  );
  echo json_encode($responce);
  exit;


} else {
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
?>