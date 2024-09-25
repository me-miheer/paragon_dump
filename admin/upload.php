<?php
require_once("checkLogin.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles | Paragon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="section bg-info" style="position: fixed; width: 100vw; height: 100vh; margin: 0; padding: 0; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items:center;">
        <div class="data card">
            <div class="card-header">
                <i class="bi bi-arrow-left" width="50px" height="50px" onclick="location.replace('article.php')"></i>
            </div>
            <div class="card-header">
                <nobr>Choose your worksheet &nbsp; <abbr class="que" title='Headers: [ "ARTICLE", "GENDER"] ' class="initialism" style="width: 25px; height:25px; border: 1px solid black; border-radius: 50%; overflow: hidden; text-align: center;"><i class="bi bi-question m-auto"></i></abbr></nobr>
            </div>
            <form class="card-body" id="excelForm"  enctype="multipart/form-data" method="POST" action="uploads.php">
                <input type="file" id="excelFile" name="excelFile" class="form-control" accept=".csv" />
            </form>
        </div>
    </div>

    <script>
        document.getElementById("excelFile").addEventListener("change", function() {
            // Submit the form when a file is selected
            document.getElementById("excelForm").submit();
        });
    </script>

</body>

</html>