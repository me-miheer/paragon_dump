<?php
require_once("checkLogin.php");
require('../connection.php');
header('Content-Type: text/html; charset=utf-8');

// Fetch pagination parameters
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;   // Number of records per page
$skip = isset($_GET['skip']) ? intval($_GET['skip']) : 0;       // Number of records to skip
$order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'ASC'; // Order direction
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';   // Column to sort by
$limitArr = [
    '10',
    '50',
    '100',
    '200',
    '500',
    '1000'
];

$orderArr = [
    'ASC',
    'DESC'
];

// Prepare the SQL query
$querySyntax = "SELECT * FROM dump ORDER BY $sort_by $order LIMIT ?, ?";
$stmt = $mysql->prepare($querySyntax); // Use the correct variable $querySyntax instead of $sql

// Bind parameters
$stmt->bind_param("ii", $skip, $limit);

// Execute the query
$stmt->execute();

$result = $stmt->get_result();

// Fetch the result set
$data = [];

// Close the connection
$stmt->close();
$mysql->close();
?>

<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Articles | Paragon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css");
    </style>
</head>

<body class="p-4 m-auto" style="max-width: 500px; min-height: 100vh; border-left: 3px solid gray; border-right: 3px solid gray;" id="demo">
    <section id="body" class="">
        <div class="row">
            <div class="col-6" style="display: flex; justify-content: start; align-items: center;">
                <h1><b><u>Article</u></b></h1>
            </div>
            <div class="col-6" style="display: flex; justify-content: end; align-items: center;"><a href="upload.php" style="text-decoration: underline;">Upload File</a></div>
        </div>

        <div class="hstack gap-3 mt-3">
            <select name="" id="hstack-limit" class="btn btn-secondary" onchange="redirectData()">
                <?php
                echo '<option value="' . $limit . '" selected>' . $limit . '</option>';
                foreach ($limitArr as $row) {
                    if ($row != $limit) {
                        echo '<option value="' . $row . '">' . $row . '</option>';
                    }
                }
                ?>
            </select>
            <!-- <div class="vr"></div> -->
            <select name="" id="hstack-order" class="btn btn-outline-secondary" onchange="redirectData()">
                <?php
                echo '<option value="' . $order . '" selected>' . $order . '</option>';
                foreach ($orderArr as $row) {
                    if ($row != $order) {
                        echo '<option value="' . $row . '">' . $row . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="overflow-auto">
            <table class="table table-stripped mt-3" id="myTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Article</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- List -->
                    <?php
                    $i = 1;

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        // Loop through the results if data exists
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $row['article'] ?></td>
                                <td><?= $row['gender'] ?></td>
                            </tr>
                        <?php
                            $i++;
                        }
                    } else {
                        // Display this if no results are found
                        ?>
                        <tr>
                            <td colspan="3">No data found</td>
                        </tr>
                    <?php
                    }
                    ?>
                    <!-- List -->
                </tbody>
            </table>
        </div>
        <!-- 
            <div class="row">
                <div class="col-12" style="display: flex; justify-content: end; align-items: flex-end;">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div> -->
    </section>
    <script>
        function redirectData() {
            let hstackLimit = document.getElementById("hstack-limit").value;
            let hstackOrder = document.getElementById("hstack-order").value;
            location.replace("article.php?skip=0&limit=" + hstackLimit + "&order=" + hstackOrder);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>