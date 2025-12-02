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
    
    /* Enhanced Export Modal Styles */
    .export-modal-dialog {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    
    .export-modal-content {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 500px;
    }
    
    .export-modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 15px 15px 0 0;
      border: none;
      padding: 25px;
    }
    
    .export-modal-title {
      font-size: 24px;
      font-weight: bold;
      margin: 0;
    }
    
    .export-modal-body {
      padding: 30px;
      text-align: center;
    }
    
    .export-options {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-top: 30px;
    }
    
    .export-option {
      flex: 1;
      padding: 25px;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid #e0e0e0;
      background: #f9f9f9;
      text-align: center;
      text-decoration: none;
      color: #333;
    }
    
    .export-option:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      border-color: #667eea;
    }
    
    .export-option.excel {
      border-left: 4px solid #21a366;
    }
    
    .export-option.excel:hover {
      background: #f0f7f4;
      border-color: #21a366;
    }
    
    .export-option.pdf {
      border-left: 4px solid #d32f2f;
    }
    
    .export-option.pdf:hover {
      background: #fef0f0;
      border-color: #d32f2f;
    }
    
    .export-icon {
      font-size: 48px;
      margin-bottom: 15px;
      display: block;
    }
    
    .export-option.excel .export-icon {
      color: #21a366;
    }
    
    .export-option.pdf .export-icon {
      color: #d32f2f;
    }
    
    .export-option-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 8px;
      display: block;
    }
    
    .export-option-desc {
      font-size: 13px;
      color: #666;
    }
    
    .export-modal-footer {
      padding: 20px;
      border-top: 1px solid #e0e0e0;
      text-align: center;
    }
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
          showExportPopup(filterForm);
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

    function showExportPopup(filterForm) {
      // Create enhanced modal HTML
      const modalHtml = `
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
          <div class="export-modal-dialog modal-dialog">
            <div class="export-modal-content modal-content">
              <div class="export-modal-header modal-header">
                <h5 class="export-modal-title" id="exportModalLabel">
                  <i class="bi bi-download"></i> Choose Export Format
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="export-modal-body modal-body">
                <p style="color: #666; margin-bottom: 20px;">Select how you'd like to export your data</p>
                <div class="export-options">
                  <a class="export-option excel" onclick="exportAsExcel('${filterForm}')">
                    <i class="bi bi-file-earmark-spreadsheet export-icon"></i>
                    <span class="export-option-title">Excel</span>
                    <span class="export-option-desc">.xls format</span>
                  </a>
                  <a class="export-option pdf" onclick="exportAsPDF('${filterForm}')">
                    <i class="bi bi-file-earmark-pdf export-icon"></i>
                    <span class="export-option-title">PDF</span>
                    <span class="export-option-desc">.pdf format</span>
                  </a>
                </div>
              </div>
              <div class="export-modal-footer modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      `;

      // Remove existing modal if present
      const existingModal = document.getElementById('exportModal');
      if (existingModal) {
        existingModal.remove();
      }

      // Add modal to DOM
      document.body.insertAdjacentHTML('beforeend', modalHtml);

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('exportModal'));
      modal.show();

      // Remove modal from DOM when hidden
      document.getElementById('exportModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
      });
    }

    function exportAsExcel(filterForm) {
      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
      modal.hide();
      
      // Redirect to Excel export
      window.location.href = "fetchDataExport.php?" + filterForm;
    }

    function exportAsPDF(filterForm) {
      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
      modal.hide();
      
      // Redirect to PDF export
      window.location.href = "exportAsPDF.php?" + filterForm;
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>