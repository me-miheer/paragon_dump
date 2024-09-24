<?php
require('../connection.php');
header("Content-Type: text/html");
$name = $_POST['name'];
$app_update = $_POST['app_update'];
$app_update_need = $_POST['app_update_need'];
$app_update_url = $_POST['app_update_url'];
$app_update_version = $_POST['app_update_version'];
$whats_new = $_POST['whats_new'];
$query = "UPDATE app_settings SET
app_name = '$name',
app_update = '$app_update',
app_update_need = '$app_update_need',
app_update_url = '$app_update_url',
app_version = '$app_update_version',
whats_new = '$whats_new'
where id = 1";
$runquery = mysqli_query($mysql,$query);
if($runquery){
?>
<div class="row p-0 m-0">
    <div class="col" style="text-align: center;">
        <img src="https://cdn-icons-png.flaticon.com/512/7518/7518748.png" class="m-auto" alt="success" width="150px" height="150px">
    </div>
</div>
<div class="row p-0 m-0 mt-4">
    <div class="col" style="text-align: center;">
        <b><h1>Your settings has been updated successfully</h1></b>
    </div>
</div>
<?php
}else{
    echo 'somthing went wrong';
}
?>
