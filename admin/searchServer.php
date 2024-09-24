<?php
require('../connection.php');
header("Content-Type: text/html");
?>
<?php
$term = $_POST['term'];
$query = mysqli_query($mysql,"SELECT * FROM location where location like '%$term%' or accesskey like '%$term%' order by id desc");
while($data = mysqli_fetch_assoc($query)){
?>
<li style="margin: 10px;">
  <a href="locationManage.php?id=<?=$data['id']?>" style="text-decoration:none;" class="text-dark card rounded-pill p-2 items-server" style="cursor: pointer;">
    <div class="row p-0 m-0">
      <div class="col-4" style="display: flex; justify-content: center; align-items: center;">
      <img src="locateServer.png" width="50px" height="50px" style="border-radius: 50px; overflow: hidden;" alt="photo_<?=$data['location']?>">
      </div>
      <div class="col-8">
        <h1 class="m-0 p-0"><?=$data['location']?></h1>
        <strong class="text-secondary"><?=$data['accesskey']?></strong>
      </div>
  </div>
  </a>
</li>
<?php
}
?>