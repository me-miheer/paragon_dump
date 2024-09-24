<?php
require_once("checkLogin.php");
require('../connection.php');
header("Content-Type: text/html");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home | Paragon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css");
  </style>
  <meta name="robots" content="noindex">
  <style>
    input {
      outline: 2px solid black;
    }

    li {
      list-style: none;
      list-style-type: none;
    }

    .items-server:hover {
      background-color: ghostwhite;
    }
  </style>
</head>

<body class="p-3 m-auto" style="max-width: 500px; min-height: 100vh; border-left: 3px solid gray; border-right: 3px solid gray;">
  <div class="row p-0 m-0">
    <div class="col-6">
      <h1><b>Admin</b></h1>
    </div>
    <div class="col-6 p-0 pe-2" style="text-align: right;">
      <div class="dropdown">
        <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person"></i>
        </a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="sizelist.php">Size List</a></li>
          <li><a class="dropdown-item" href="article.php">Articles</a></li>
          <li><a class="dropdown-item" href="settings.php">Settings</a></li>
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </div>
      <!-- <a href="settings.php"><i class="bi bi-gear-wide-connected text-dark" style="font-size: 30px; cursor: pointer;"></i></a> -->
    </div>
    <div class="row mt-3 mb-3">
      <div class="col-6 text-center">
        <div class="card bg-light bg-gradient text-secondary border-2 border-secondary p-2">Visitors<br>100</div>
      </div>
      <div class="col-6 text-center">
        <div class="card bg-light bg-gradient text-secondary border-2 border-secondary p-2">Scans<br>100</div>
      </div>
    </div>
    <div class="row p-0 m-0">
      <div class="col">
        <a class="btn btn-success rounded-pill p-3 mt-2" href="createServer.php" style="width: 100%;">+ Create a new server</a>
      </div>
    </div>
    <div class="row p-0 m-0 mt-2">
      <div class="col text-secondary text-center">
        <h1>Or</h1>
      </div>
    </div>
    <div class="row p-0 m-0">
      <div class="col">
        <input type="text" class="form-control p-3 mt-2 rounded-pill" id="formutf" onkeyup="serchme()" placeholder="Search your server here">
      </div>
    </div>
    <div class="col-6 mt-3">
      <h1><b>Servers</b></h1>
    </div>
    <ul id="serverlist">
      <?php
      $query = mysqli_query($mysql, "SELECT * FROM location order by id desc");
      while ($data = mysqli_fetch_assoc($query)) {
      ?>
        <li style="margin: 10px;">
          <a href="locationManager.php?id=<?= $data['id'] ?>" style="text-decoration:none;" class="text-dark card rounded-pill p-2 items-server" style="cursor: pointer;">
            <div class="row p-0 m-0">
              <div class="col-4" style="display: flex; justify-content: center; align-items: center;">
                <img src="locateServer.png" width="50px" height="50px" style="border-radius: 50px; overflow: hidden;" alt="photo_<?= $data['location'] ?>">
              </div>
              <div class="col-8">
                <h1 class="m-0 p-0"><?= $data['location'] ?></h1>
                <strong class="text-secondary"><?= $data['accesskey'] ?></strong>
              </div>
            </div>
          </a>
        </li>
      <?php
      }
      ?>
    </ul>
  </div>
  <script>
    function serchme() {
      document.getElementById("serverlist").innerHTML = '<div class="text-center"><div class="spinner-grow text-center" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>';
      let data = document.getElementById("formutf").value;
      const xhttp = new XMLHttpRequest();
      xhttp.onload = function() {
        document.getElementById("serverlist").innerHTML = this.responseText;
      }
      xhttp.open("POST", "searchServer.php");
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send('term=' + data);
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>