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
    
    
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=".$_REQUEST['month'].'_'.$locationName.".xls");
    
    if(isset($_REQUEST['mobile'])){
        $mobile = $_REQUEST['mobile'];
        $query = mysqli_query($mysql,"SELECT * FROM csv_data where MONTH(acutal_date) = '$month' and YEAR(acutal_date) = '$year' and mobile_number = '$mobile' and server_key = '$accesskey' order by id desc");
    }else{
        $query = mysqli_query($mysql,"SELECT * FROM csv_data where MONTH(acutal_date) = '$month' and YEAR(acutal_date) = '$year' and server_key = '$accesskey' order by id desc");
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

    while($data = mysqli_fetch_assoc($query)){
?>
        <tr>
            <td><?=$data['qr']?></td>
            <td><?=$data['dealer']?></td>
            <td><?=$data['shop_name']?></td>
            <td><?=$data['quantity']?></td>
            <td><?=$data['mobile_number']?></td>
            <td><?=$data['type']?></td>
            <td><?=$data['town']?></td>
            <td><?=$data['consumer']?></td>
            <td><?=$data['consumer_size']?></td>
            <td><?=$data['server']?></td>
            <td><?=$data['time']?></td>
        </tr>
<?php
}
?>
    </tbody>
</table>