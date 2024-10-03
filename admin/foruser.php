<?php
require('../connection.php');
header("Content-Type: text/html");
if(!isset($_GET['id'])){
    header('location:index.php');
}
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($mysql,"SELECT * FROM location where accesskey = '$id' limit 1"));
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css");</style>
    <meta name="robots" content="noindex">
    <style>
      input{
        outline: 2px solid black;
      }
      li{
        list-style: none;
        list-style-type: none;
      }
      .items-server:hover{
        background-color: ghostwhite;
      }
    </style>
  </head>
  <body class="p-3" id="demo">
    <div class="row p-0 m-0">
      <div class="col-6 p-0 pe-2" style="text-align: right;">
      </div>
      <div class="row p-0 m-0 mt-2">
        <div class="col text-secondary text-center">
          <h1>Select Month</h1>
      </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col">
          <input type="month" id="formutf" class="form-control p-3 mt-2 rounded-pill" placeholder="Search your server here">
        </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col">
          <button class="btn btn-success rounded-pill p-3 mt-2" style="width: 100%;" onclick="exportme()">Export data</button>
        </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col">
          <button class="btn btn-danger rounded-pill p-3 mt-2" style="width: 100%;" onclick="deleteme()">Delete data</button>
        </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col">
            <?php if($data['accesskey'] !== 'scan_000000'){ ?>
          <button class="btn btn-danger rounded-pill p-3 mt-2" style=" position: absolute; bottom: 15px;" onclick="deleteserver()">Delete Server</button>
           <?php } ?>
        </div>
      </div>
    </div>
    <script>
        function exportme(){
            let data = document.getElementById("formutf").value;
            if(data === ''){
                alert('Please select a month first');
            }else{
                location.href = 'fetchData.php?month='+data+"&id=<?=$data['id']?>&mobile=<?=$_REQUEST['mobile']?>";
            }
        }
        function deleteme(){
            let data = document.getElementById("formutf").value;
            if(data === ''){
                alert('Please select a month first');
            }else{
                let person = prompt('Please type "Yes" to delete data');
                if(person === 'Yes'){
                    const xhttp = new XMLHttpRequest();
                    xhttp.onload = function() {
                        document.getElementById("demo").innerHTML = this.responseText;
                        }
                    xhttp.open("POST", "deleteData.php");
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send('month='+data+"&id=<?=$data['id']?>&mobile=<?=$_REQUEST['mobile']?>");
                }
            }
        }
        function deleteserver(){
            let person = prompt('Please type "Yes" to this Server');
            if(person === 'Yes'){
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    if(this.responseText === 'true'){
                        alert('deleted successfully');
                        location.href = 'index.php'
                    }else{
                        alert(this.responseText);
                    }
                    }
                xhttp.open("POST", "deleteServer.php");
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("id=<?=$data['id']?>");
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>