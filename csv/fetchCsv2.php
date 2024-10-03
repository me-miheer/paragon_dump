<?php
require('../connection.php');

header('Content-Type: text/html; charset=utf-8');

$present_date = date('Y-m-d');

$createuserquery = "SELECT * FROM csv_data where acutal_date = '$present_date' order by id desc limit 10";

if (isset($_GET['serverkey']) && isset($_GET['offset']) && isset($_GET['limit']) && isset($_GET['mobile'])) {
  $serverkey = mysqli_real_escape_string($mysql, trim($_GET['serverkey']));
  $offset = mysqli_real_escape_string($mysql, trim($_GET['offset']));
  $limit = mysqli_real_escape_string($mysql, trim($_GET['limit']));
  $mobile = mysqli_real_escape_string($mysql, trim($_GET['mobile']));
  $createuserquery = "SELECT * FROM csv_data where server_key = '$serverkey' and mobile_number = '$mobile' and acutal_date = '$present_date' order by id desc";
  // $createuserquery = "SELECT * FROM csv_data where server_key = '$serverkey' and mobile_number = '$mobile' and acutal_date = '$present_date' order by id desc limit $offset,$limit";
}


$runcreateusersquery = mysqli_query($mysql, $createuserquery);
// check if user created or not.
if ($runcreateusersquery) {
  $datajson = array();
  $countdata = 0;
  while ($data = mysqli_fetch_assoc($runcreateusersquery)) {
    $datajson[] = $data;
    $countdata++;
  }


  $html2 = "";
  $qtyplusplus = 0;
  $setplusplus = 0;
  $pairplusplus = 0;
  $cartonplusplus = 0;
  foreach ($datajson as $dataa) {
    $html2 .= "<tr class='p-0 m-0 hover-style-tr' style=\"cursor:pointer;\" onclick='getTheData(\"" . $dataa['id'] . "\", \"" . $dataa['consumer'] . "\")'>";
    $html2  .= "<td class='p-1 m-0 bg-transparent'><nobr class='p-0 m-0'>" . $dataa['qr'] . "</nobr></td>";
    $html2 .= "<td class='p-1 m-0 bg-transparent'><nobr class='p-0 m-0'>" . $dataa['consumer_size'] . "</nobr></td>";
    $html2 .= "<td class='p-1 m-0 bg-transparent'><nobr class='p-0 m-0'>" . $dataa['quantity'] . "</nobr></td>";
    $html2 .= "<td class='p-1 m-0 bg-transparent'><nobr class='p-0 m-0'>" . $dataa['type'] . "</nobr></td>";
    $html2 .= "</tr>";
    $qtyplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    if($dataa['type'] == 'Set'){
        $setplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    }
    if($dataa['type'] == 'Pair'){
        $pairplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    }
    if($dataa['type'] == 'Carton'){
        $cartonplusplus += $dataa['quantity'] ? $dataa['quantity'] : 0;
    }
  }

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
      .hover-style-tr:hover {
        background-color: antiquewhite;
      }

      /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      /* Firefox */
      input[type=number] {
        -moz-appearance: textfield;
      }

      .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Ensure it appears on top */
      }
      .modal-backdrop.show {
    opacity: 0%;
}
    </style>
    <title>Paragon Home</title>
  </head>

  <body class="p-0 m-0 mt-4" style="font-family: 'Courier New', Courier, monospace; font-size:12px;">
    <table class="table table-bordered p-0 m-0">
      <thead class="p-0 m-0" style="background-color: rgb(93, 93, 238); color: aliceblue;">
        <tr class="p-0 m-0">
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-light" style="background-color:#0D7C66;">
            <nobr>TOTAL QTY</nobr>
          </th>
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-light" style="background-color:#0D7C66;">
            <nobr><?= $qtyplusplus ?></nobr>
          </th>
        </tr>
        <tr class="p-0 m-0">
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-dark">
            <nobr>TOTAL SETs</nobr>
          </th>
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-dark">
            <nobr><?= $setplusplus ?></nobr>
          </th>
        </tr>
        <tr class="p-0 m-0">
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-dark">
            <nobr>TOTAL PAIRs</nobr>
          </th>
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-dark">
            <nobr><?= $pairplusplus ?></nobr>
          </th>
        </tr>
        <tr class="p-0 m-0">
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-dark">
            <nobr>TOTAL CARTONs</nobr>
          </th>
          <th colspan="2" class="p-0 m-0 bg-gradient p-1 text-dark">
            <nobr><?= $cartonplusplus ?></nobr>
          </th>
        </tr>
        <tr class="p-0 m-0">
          <th class="bg-danger p-0 m-0 bg-gradient p-1 text-light">
            <nobr>ARTICLE</nobr>
          </th>
          <th class="bg-danger p-0 m-0 bg-gradient p-1 text-light">
            <nobr>SIZE</nobr>
          </th>
          <th class="bg-danger p-0 m-0 bg-gradient p-1 text-light">
            <nobr>QTY</nobr>
          </th>
          <th class="bg-danger p-0 m-0 bg-gradient p-1 text-light">
            <nobr>UNIT</nobr>
          </th>
        </tr>
      </thead>
      <tbody class="p-0 m-0">
        <?= $html2 ?>
      </tbody>
    </table>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <form class="modal-dialog modal-dialog-scrollable" method="POST" action="javascript:void(0)" onsubmit="submitthis()" id="formId">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">ARTICLE PREVIEW</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-floating mb-1">
              <input type="hidden" name="id" id="floatingInputId">
              <input type="text" name="qr" class="form-control" style="background-color: #ebebeb;" id="floatingInputArticle" placeholder="ARTICLE" readonly>
              <label for="floatingInputArticle">ARTICLE</label>
            </div>
            <div class="form-floating mb-1">
              <input type="number" name="quantity" class="form-control" id="floatingInputQuantity" placeholder="QUANTITY">
              <label for="floatingInputQuantity">QUANTITY</label>
            </div>
            <div class="form-floating mb-1">
              <select class="form-select" name="type" id="floatingSelectType" aria-label="TYPE">
                <option value="" disabled>SELECT TYPE</option>
                <option value="Set">Set</option>
                <option value="Pair">Pair</option>
                <option value="Carton">Carton</option>
              </select>
              <label for="floatingSelectType">TYPE</label>
            </div>
            <div class="form-floating mb-1">
              <input type="number" name="consumer" class="form-control" id="floatingSelectGender" placeholder="CONSUMER">
              <label for="floatingInputQuantity">CONSUMER</label>
            </div>
            <div class="form-floating">
              <select class="form-select" name="consumer_size" id="floatingSelectForSize" aria-label="SIZE">

              </select>
              <label for="floatingSelectForSize">SIZE</label>
            </div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-danger me-auto" onclick="deletethis()"><i class="bi bi-trash"></i></a>
            <a class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</a>
            <button type="submit" class="btn btn-dark">UPDATE</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Full-Screen Loader -->
    <div class="loader" id="loader" style="display: none;">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
      function getTheData(id, consumer) {
        document.getElementById('loader').style.display = "flex";
        const xhttp1 = new XMLHttpRequest();
        xhttp1.onload = function() {
          let genderResult = JSON.parse(this.responseText);
          let sizeResult = "";
          document.getElementById("floatingSelectForSize").innerHTML = null;
          genderResult.forEach(item => {
            const option = document.createElement('option');
            option.textContent = item.data;
            option.value = item.data;
            if (item.data == "SELECT ANY") {
              option.disabled = true;
            }
            document.getElementById("floatingSelectForSize").appendChild(option);
          });
          const xhttp2 = new XMLHttpRequest();
          xhttp2.onload = function() {
            let csvResult = JSON.parse(this.responseText);
            document.getElementById("floatingInputId").value = csvResult.id;
            document.getElementById("floatingInputArticle").value = csvResult.qr;
            document.getElementById("floatingInputQuantity").value = parseInt(csvResult.quantity);
            document.getElementById("floatingSelectType").value = csvResult.type;
            document.getElementById("floatingSelectGender").value = csvResult.consumer;
            document.getElementById("floatingSelectForSize").value = csvResult.consumer_size;
            let myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            document.getElementById('loader').style.display = "none";

            function openModal() {
              myModal.show();
            }
            openModal();
          }
          xhttp2.open("GET", "getArticleViaId.php?dID=" + id);
          xhttp2.send();
        }
        xhttp1.open("GET", "https://paragonteam.com/api/v2/settings/appUsers?key=" + consumer);
        xhttp1.send();
      }

      function changeSize(gender) {
        document.getElementById('loader').style.display = "flex";
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          let genderResult = JSON.parse(this.responseText);
          let sizeResult = "";
          document.getElementById("floatingSelectForSize").innerHTML = null;
          genderResult.forEach(item => {
            const option = document.createElement('option');
            option.textContent = item.data;
            option.value = item.data;
            if (item.data == "SELECT ANY") {
              option.disabled = true;
            }
            document.getElementById("floatingSelectForSize").appendChild(option);
          });
          document.getElementById('loader').style.display = "none";
        }
        xhttp.open("GET", "https://paragonteam.com/api/v2/settings/appUsers?key=" + gender);
        xhttp.send();
      }

      function submitthis() {
        document.getElementById('loader').style.display = "flex";
        const xhttp3 = new XMLHttpRequest();
        xhttp3.onload = function() {
          location.reload();
        }
        xhttp3.open("GET", "sumitAericleViaId.php?"+$("#formId").serialize());
        xhttp3.send();
      }
    
      function deletethis() {
        document.getElementById('loader').style.display = "flex";
        const xhttp4 = new XMLHttpRequest();
        xhttp4.onload = function() {
          location.reload();
        }
        xhttp4.open("GET", "deleteAericleViaId.php?id="+$("#floatingInputId").val());
        xhttp4.send();
      }
    </script>
  </body>

  </html>

<?php

} else {
  http_response_code(503);
  $responce = array(
    'status' => 'false',
    'response_code' => '503',
    'task_status' => 'false',
    'message' => 'Service unavailable'
  );
  echo json_encode($responce);
  exit;
}
?>