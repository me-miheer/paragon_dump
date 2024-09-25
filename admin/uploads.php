<?php
require('../connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');




// Define the expected headers for validation
$expectedHeaders = ['Article', 'Gender'];
$response = [];

// Check if the file has been uploaded without errors
if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == 0) {

    // Get the file path
    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];

    // Check if the file is a CSV
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    if ($fileType == 'csv') {
        // Open the file
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {

            // Read the first row (headers)
            $headers = fgetcsv($handle, 1000, ",");

            // Validate if headers match the expected format
            if ($headers === $expectedHeaders) {

                $txt = [];
                $successJobs = 0;
                $failedJobs = 0;

                // Loop through the remaining CSV rows
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    try {

                        if(empty(trim($data[0])) || empty(trim($data[1]))){
                            
                            $txt[] = "Error: Any of two cells is empty.";

                        }else{

                            $article = mysqli_real_escape_string($mysql, !empty(trim($data[0])) ? $data[0] : null);
                            $gender = mysqli_real_escape_string($mysql, !empty(trim($data[1])) ? $data[1] : null);
    
                            $mysqliQuery = mysqli_query($mysql, "INSERT INTO dump ( article, gender) VALUES ( '$article', '$gender' )");
    
                            $txt[] = "Success : Article : has been inserted successfully!";
                            $successJobs++;
                        }

                    } catch (Exception $th) {

                        $txt[] = $th->getMessage();
                        $failedJobs++;

                    }
                }

                $response[] = array(
                    "suceessJobs" => $successJobs,
                    "failedJobs" => $failedJobs,
                    "Jobs" => $txt
                );

            } else {

                $response[] = array(
                    "status" => false,
                    "message" => "Error: Incorrect file format. Expected headers: ['Article', 'Gender']."
                );
            }

            // Close the file
            fclose($handle);
        } else {

            $response[] = array(
                "status" => false,
                "message" => "Error:unable to open or read the file."
            );
        }
    } else {

        $response[] = array(
            "status" => false,
            "message" => "Error:Invalid CSV file."
        );
    }
} else {

    $response[] = array(
        "status" => false,
        "message" => "Error: No file uploaded or an error occurred during the upload."
    );
}
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
            <div class="card-body" style="height: 400px; width: 400px;">
                <div class="data w-100" style="height: 100%; overflow: scroll">
                   <?="<pre>".json_encode($response, JSON_PRETTY_PRINT)."<pre>"?> 
                </div>
            </div>
            <div class="card-header">
                <button class="btn btn-success bg-gradient w-100 p-2"  onclick="location.replace('article.php')">Aknowledged</button>
            </div>
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