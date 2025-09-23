<?php
require_once("checkLogin.php");
require('../connection.php'); // Make sure $mysql is mysqli connection
header('Content-Type: text/html; charset=utf-8');

// Fetch pagination parameters
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$skip = isset($_GET['skip']) ? intval($_GET['skip']) : 0;
$order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'ASC';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';

$limitArr = ['10','50','100','200','500','1000'];
$orderArr = ['ASC','DESC'];

// SQL query
$querySyntax = "SELECT * FROM dumpV2 ORDER BY $sort_by $order LIMIT $skip, $limit";

// Run query
$result = mysqli_query($mysql, $querySyntax);
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
    <section id="body">
        <div class="row">
            <div class="col-6" style="display: flex; justify-content: start; align-items: center;">
                <h1><b><u>ArticleV2</u></b></h1>
            </div>
            <div class="col-6" style="display: flex; justify-content: end; align-items: center;">
                <a href="uploadV2.php" style="text-decoration: underline;">Upload File</a>
            </div>
        </div>

        <div class="hstack gap-3 mt-3">
            <select id="hstack-limit" class="btn btn-secondary" onchange="redirectData()">
                <?php
                echo '<option value="'.$limit.'" selected>'.$limit.'</option>';
                foreach ($limitArr as $row) {
                    if ($row != $limit) {
                        echo '<option value="'.$row.'">'.$row.'</option>';
                    }
                }
                ?>
            </select>

            <select id="hstack-order" class="btn btn-outline-secondary" onchange="redirectData()">
                <?php
                echo '<option value="'.$order.'" selected>'.$order.'</option>';
                foreach ($orderArr as $row) {
                    if ($row != $order) {
                        echo '<option value="'.$row.'">'.$row.'</option>';
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
                        <th>Size</th>
                        <th>Scheme</th>
                        <th>QR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $row['article'] ?></td>
                                <td><?= $row['size'] ?></td>
                                <td><?= $row['scheme'] ?></td>
                                <td>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=<?= $row['article'] ?>" 
                                         alt="qr" width="50" height="50">
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">No data found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        function redirectData() {
            let hstackLimit = document.getElementById("hstack-limit").value;
            let hstackOrder = document.getElementById("hstack-order").value;
            location.replace("articleV2.php?skip=0&limit=" + hstackLimit + "&order=" + hstackOrder);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
