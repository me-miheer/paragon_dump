<?php
require('../connection.php');
header("Content-Type: text/html");

$id = $_REQUEST['filter_id'];
$data = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM location where id = '$id' limit 1"));


$accesskey = $data['accesskey'];
$locationName = $data['location'];

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$_REQUEST['filter_time_value'].'_'.$locationName.".xls");

$query_str = "SELECT * FROM csv_data where ";

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

$query_str .= "and server_key = '$accesskey' order by id desc";

try {
    $query = mysqli_query($mysql, $query_str);
} catch (\Throwable $th) {
    print_r($th);
    exit;
}

?>
<table>
    <thead>
        <tr>
            <th>QR CODE</th>
            <th>DEALER / DEPOT</th>
            <th>SHOP NAME</th>
            <th>QUANTITY</th>
            <th>MOBILE NUMBER</th>
            <th>TYPE</th>
            <th>TOWN</th>
            <th>CONSUMER</th>
            <th>CONSUMER SIZE</th>
            <th>SERVER</th>
            <th>TIME</th>
        </tr>
    </thead>
    <tbody>
        <?php

        while ($data = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td><?= $data['qr'] ?></td>
                <td><?= $data['dealer'] ?></td>
                <td><?= $data['shop_name'] ?></td>
                <td><?= $data['quantity'] ?></td>
                <td><?= $data['mobile_number'] ?></td>
                <td><?= $data['type'] ?></td>
                <td><?= $data['town'] ?></td>
                <td><?= $data['consumer'] ?></td>
                <td><?= $data['consumer_size'] ?></td>
                <td><?= $data['server'] ?></td>
                <td><?= $data['time'] ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>