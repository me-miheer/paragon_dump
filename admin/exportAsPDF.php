<?php

require_once '../vendor/autoload.php';
require('../connection.php');

$mpdf = new mPDF();

$id = $_REQUEST['filter_id'];
$stmt = $mysql->prepare("SELECT * FROM location where id = ? limit 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$location_data = $result->fetch_assoc();

$accesskey = $location_data['accesskey'];
$locationName = $location_data['location'];

$query_str = "SELECT * FROM csv_data_new where ";

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
    $query = $mysql->query($query_str);
} catch (\Throwable $th) {
    print_r($th);
    exit;
}

$data = [
    'company_name' => 'Paragon',
    'company_address' => 'Made with <span class="heart">❤️</span> in India<br><small style="font-size: 10px; color: #666;">Generated on ' . date('d-m-Y h:i A') . '</small>'
];

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { border-bottom: 3px solid #2c5aa0; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { float: left; width: 150px; height: 80px; }
        .company-info { float: right; text-align: right; }
        .company-name { font-size: 24px; font-weight: bold; color: #2c5aa0; margin-bottom: 5px; }

        .invoice-details { width: 100%; margin-bottom: 30px; }
        .invoice-details td { padding: 8px 0; }
        .bill-to { background: #f8f9fa; padding: 15px; border-left: 4px solid #2c5aa0; margin-bottom: 30px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #2c5aa0; color: white; padding: 12px; text-align: left; }
        .items-table td { padding: 10px 12px; border-bottom: 1px solid #ddd; }
        .items-table tr:nth-child(even) { background: #f8f9fa; }
        .totals { float: right; width: 300px; }
        .totals table { width: 100%; }
        .totals td { padding: 8px 12px; border-bottom: 1px solid #ddd; }
        .total-row { background: #2c5aa0; color: white; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px; }
        .clearfix::after { content: ""; display: table; clear: both; }
        .heart { color: red; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="logo"><img src="seeklogo.png" style="max-width: 150px; max-height: 80px;"></div>
        <div class="company-info">
            <div class="company-name">' . $data['company_name'] . '</div>
            <div>' . $data['company_address'] . '</div>
        </div>
    </div>
    
    
    <table class="items-table">
        <thead>
            <tr>
                <th>QR CODE</th>
                <th>DEALER / DEPOT</th>
                <th>SHOP NAME</th>
                <th>QUANTITY</th>
                <th>MOBILE NUMBER</th>
                <th>TYPE</th>
                <th>TOWN</th>
                <th>MEET TYPE</th>
                <th>SCHEME</th>
                <th>CONSUMER SIZE</th>
                <th>SERVER</th>
                <th>TIME</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $query->fetch_assoc()) {
    $html .= '
            <tr>
                <td>' . $row['qr'] . '</td>
                <td>' . $row['dealer'] . '</td>
                <td>' . $row['shop_name'] . '</td>
                <td>' . $row['quantity'] . '</td>
                <td>' . $row['mobile_number'] . '</td>
                <td>' . $row['type'] . '</td>
                <td>' . $row['town'] . '</td>
                <td>' . $row['retailType'] . '</td>
                <td>' . $row['consumer'] . '</td>
                <td>' . $row['consumer_size'] . '</td>
                <td>' . $row['server'] . '</td>
                <td>' . $row['time'] . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>
';

$mpdf->WriteHTML($html);
$mpdf->Output($_REQUEST['filter_time_value'].'_'.$locationName.'.pdf', 'I'); // 'D' for download
