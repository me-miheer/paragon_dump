<?php
require('../connection.php');
header("Content-Type: text/html");

$id = $_REQUEST['filter_id'];
$data = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM location where id = '$id' limit 1"));


$accesskey = $data['accesskey'];
$locationName = $data['location'];

$query_str = "DELETE FROM csv_data where ";

if ($_REQUEST['filter_time_type'] == 'date') {
    $array = explode('-', $_REQUEST['filter_time_value']);
    $year = $array[0];
    $month = $array[1];
    $day = $array[2];
    $query_str .= "DAY(acutal_date) = '$day' and MONTH(acutal_date) = '$month' and YEAR(acutal_date) = '$year'";
}

if ($_REQUEST['filter_time_type'] == 'month') {
    $array = explode('-', $_REQUEST['filter_time_value']);
    $year = $array[0];
    $month = $array[1];
    $query_str .= "MONTH(acutal_date) = '$month' and YEAR(acutal_date) = '$year'";
}

if (!empty($_REQUEST['filter_mobile_check']) && !empty($_REQUEST['filter_mobile'])) {
    $mobile = $_REQUEST['filter_mobile'];
    $query_str .= " and mobile_number = '$mobile'";
}

$query_str .= "and server_key = '$accesskey'";

try {
    $query = mysqli_query($mysql, $query_str);
?>
<div class="row p-0 m-0">
    <div class="col" style="text-align: center;">
        <img src="	https://cdn-icons-png.flaticon.com/512/2420/2420620.png" class="m-auto" alt="success" width="150px" height="150px">
    </div>
</div>
<div class="row p-0 m-0 mt-4">
    <div class="col" style="text-align: center;">
        <b><h1>Deleted Successfully</h1></b>
    </div>
</div>
<?php
} catch (\Throwable $th) {
    print_r($th);
    exit;
}
?>
