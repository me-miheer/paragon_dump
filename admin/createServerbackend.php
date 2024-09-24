<?php
require('../connection.php');
header("Content-Type: text/html");
$serverName = mysqli_real_escape_string($mysql,trim($_POST['myCountry']));
$key = mysqli_fetch_assoc(mysqli_query($mysql,"SELECT * FROM location WHERE location LIKE '$serverName' ORDER BY id DESC LIMIT 1"))['location'];
if(!empty($key)){
    ?>
<div class="row p-0 m-0">
    <div class="col" style="text-align: center;">
        <img src="https://cdn-icons-png.flaticon.com/512/1680/1680012.png" class="m-auto" alt="success" width="150px" height="150px">
    </div>
</div>
<div class="row p-0 m-0 mt-4">
    <div class="col" style="text-align: center;">
        <b><h1>Following server already exists</h1></b>
    </div>
</div>
    <?php
    exit;
}
$key = mysqli_fetch_assoc(mysqli_query($mysql,'SELECT * FROM location order by id desc limit 1'))['id'];
$accesskey = 'scan_'.rand(10000,99999).$key;
$query = "INSERT INTO location 
(location,accesskey)
VALUES
('$serverName','$accesskey')";
$runquery = mysqli_query($mysql,$query);
if($runquery){
?>
<div class="row p-0 m-0">
    <div class="col" style="text-align: center;">
        <img src="	https://cdn-icons-png.flaticon.com/512/5709/5709755.png" class="m-auto" alt="success" width="150px" height="150px">
    </div>
</div>
<div class="row p-0 m-0 mt-4">
    <div class="col" style="text-align: center;">
        <b><h1>Your new server has been created successfully</h1></b>
    </div>
</div>
<?php
}else{
    echo 'somthing went wrong';
}
?>
