<?php
require('../connection.php');
header("Content-Type: text/html");
if(!isset($_REQUEST['month'])){
    echo 'Invalid Parameters';
    exit;
}
if(!isset($_REQUEST['id'])){
    echo 'Invalid Parameters';
    exit;
}
$array = explode('-',$_REQUEST['month']);
$month = $array[1];
$year = $array[0];
$id = $_REQUEST['id'];
$data = mysqli_fetch_assoc(mysqli_query($mysql,"SELECT * FROM location where id = '$id' limit 1"));

$accesskey = $data['accesskey'];
$locationName = $data['location'];

if(isset($_REQUEST['mobile'])){
    $mobile = $_REQUEST['mobile'];
    $query = "DELETE FROM csv_data where MONTH(acutal_date) = '$month' and YEAR(acutal_date) = '$year' and mobile_number = '$mobile' and server_key = '$accesskey'";
}else{
    $query = "DELETE FROM csv_data where MONTH(acutal_date) = '$month' and YEAR(acutal_date) = '$year' and server_key = '$accesskey'";
}
$runquery = mysqli_query($mysql,$query);
if($runquery){
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
}else{
    echo 'somthing went wrong';
}
?>
