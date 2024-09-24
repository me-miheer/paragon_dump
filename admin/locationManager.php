<?php
require_once("checkLogin.php");
require('../connection.php');
header("Content-Type: text/html");
if (!isset($_GET['id'])) {
  header('location:index.php');
}
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM location where id = '$id' limit 1"));
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Warehouse | Paragon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css");
  </style>
  <meta name="robots" content="noindex">
</head>

<body class="p-3" id="demo">
  <!-- navbar -->
  <div class="row">
    <div class="col-6" style="display: flex; justify-content: start; align-items: center;">
      <h1 class="p-0 m-0"><b><u>Data Center</u></b></h1>
    </div>
    <div class="col-6" style="display: flex; justify-content: end; align-items: center;"><button class="btn btn-sm btn-outline-dark" style="font-size: 18px;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i class="bi bi-filter"></i>&nbsp;&nbsp;filter lab</button></div>
  </div>

  <!-- data body -->
  <div class="data_body overflow-auto mt-5" id="data_body">
    <div class="first_inter mt-5 text-center" id="first_inter">
      <img src="5960138.png" alt="data_labs" width="100px" height="100px">
    </div>
  </div>

  <!-- Delete Server -->

  <div class="row p-0 m-0">
    <div class="col">
      <?php if ($data['accesskey'] !== 'scan_000000') { ?>
        <button class="btn btn-danger rounded-pill p-3 mt-2" style=" position: absolute; bottom: 15px;" onclick="deleteserver()">Delete Server</button>
      <?php } ?>
    </div>
  </div>


  <!-- sidebar -->

  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">FILTERS</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-3 pb-3">

      <form action="javascript:void(0);" id="filter_form">
        <div class="input-group">
          <button class="btn btn-outline-secondary dropdown-toggle" id="filter_time_select" type="button" data-bs-toggle="dropdown" aria-expanded="false">Date</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" onclick="change_time_value('date')">Date</a></li>
            <hr>
            <li><a class="dropdown-item" onclick="change_time_value('month')">Month</a></li>
          </ul>
          <input type="date" class="form-control" id="filter_time_value" name="filter_time_value" required>
          <input type="hidden" id="filter_time_type" name="filter_time_type" value="date" required>
          <input type="hidden" id="filter_id" name="filter_id" value="<?= $_REQUEST['id'] ?>" required>
        </div>
        <span id="filter_time_value_error" class="text-danger" style="visibility:hidden; font-size: 12px;">This field is required.</span>

        <div class="input-group">
          <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" id="filter_mobile_check" name="filter_mobile_check">
          </div>
          <input type="tel" class="form-control" pattern="[0-9]{10}" id="filter_mobile" name="filter_mobile" placeholder="Mobile Number">
        </div>
        <small class="text-secondary">Format: 9087654321</small>
      </form>

      <div class="row mt-4">
        <div class="col-6 text-center"><button class="w-100 btn btn-dark" onclick="do_with_filter('view')">VIEW</button></div>
        <div class="col-6 text-center"><button class="w-100 btn btn-success" onclick="do_with_filter('export')">EXPORT</button></div>
      </div>

      <div class="row mt-3">
        <div class="col-12 text-center"><button class="w-100 btn btn-danger" onclick="do_with_filter('delete')">DELETE</button></div>
      </div>

    </div>
  </div>


  <script>
    function change_time_value(val) {
      if (val == 'date') {
        $("#filter_time_select").html("Date");
        $("#filter_time_type").val("date");
        $("#filter_time_value").attr("type", "date");
      } else {
        $("#filter_time_select").html("Month");
        $("#filter_time_type").val("month");
        $("#filter_time_value").attr("type", "month");
      }
    }

    function do_with_filter(key) {
      if ($("#filter_time_value").val() == "") {
        $("#filter_time_value_error").css("visibility", "");
      } else {
        $("#filter_time_value_error").css("visibility", "hidden");
        var offcanvasElement = document.getElementById('offcanvasExample');
        var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (!offcanvasInstance) {
          offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
        }
        offcanvasInstance.hide();

        let filterForm = $("#filter_form").serialize();
        console.log(filterForm);

        if(key == "view"){
          document.getElementById("data_body").innerHTML = '<div class="spinner-border text-primary" role="status">\
            <span class="visually-hidden">Loading...</span>\
          </div>';

          const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            document.getElementById("data_body").innerHTML = this.responseText;
          }
          xhttp.open("GET", "fetchDataView.php?" + filterForm);
          xhttp.send();
        }

        if(key == "export"){
          window.location.href = "fetchDataExport.php?" + filterForm;
        }

        if(key == "delete"){
          let access = prompt('Please type \'Yes\' to this Server'); 
          if(access == 'Yes')
          {
            window.location.href = "fetchDataDelete.php?" + filterForm;
          }
        }

      }
    }

    function deleteserver() {
      let person = prompt('Please type "Yes" to this Server');
      if (person === 'Yes') {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          if (this.responseText === 'true') {
            alert('deleted successfully');
            location.href = 'index.php'
          } else {
            alert(this.responseText);
          }
        }
        xhttp.open("POST", "deleteServer.php");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("id=<?= $_GET['id'] ?>");
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>