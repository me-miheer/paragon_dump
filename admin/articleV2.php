<?php
require_once("checkLogin.php");
require('../connection.php');
header('Content-Type: text/html; charset=utf-8');

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$skip = isset($_GET['skip']) ? intval($_GET['skip']) : 0;
$order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'ASC';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';

$limitArr = ['10', '50', '100', '200', '500', '1000'];
$orderArr = ['ASC', 'DESC'];

$querySyntax = "SELECT * FROM dumpv2 ORDER BY $sort_by $order LIMIT $skip, $limit";
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
            <div class="col-6 d-flex align-items-center">
                <h1><b><u>ArticleV2</u></b></h1>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
                <a href="uploadV2.php" style="text-decoration: underline;">Upload File</a>
            </div>
        </div>

        <div class="hstack gap-3 mt-3">
            <select id="hstack-limit" class="btn btn-secondary" onchange="redirectData()">
                <?php
                echo '<option value="' . $limit . '" selected>' . $limit . '</option>';
                foreach ($limitArr as $row) {
                    if ($row != $limit) {
                        echo '<option value="' . $row . '">' . $row . '</option>';
                    }
                }
                ?>
            </select>

            <select id="hstack-order" class="btn btn-outline-secondary" onchange="redirectData()">
                <?php
                echo '<option value="' . $order . '" selected>' . $order . '</option>';
                foreach ($orderArr as $row) {
                    if ($row != $order) {
                        echo '<option value="' . $row . '">' . $row . '</option>';
                    }
                }
                ?>
            </select>
            <a href="export_csv.php" class="btn btn-success mt-3">
                <i class="fas fa-download"></i> Export CSV
            </a>

        </div>

        <div class="overflow-auto mt-3">
            <table class="table table-stripped" id="myTable">
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
                            <tr id="row-<?= $row['id'] ?>">
                                <td><?= $i ?></td>
                                <td><?= $row['article'] ?></td>
                                <td><?= $row['size'] ?></td>
                                <td id="scheme-<?= $row['id'] ?>"><?= $row['scheme'] ?></td>
                                <td>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=<?= $row['article'] ?>" width="50" height="50" alt="qr">
                                    <br>
                                    <button class="btn btn-sm btn-warning mt-1"
                                        onclick="openSchemeModal(<?= $row['id'] ?>, '<?= $row['article'] ?>', '<?= $row['scheme'] ?>')">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                    <?php
                            $i++;
                        }
                    } else {
                        echo '<tr><td colspan="5">No data found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Scheme Modal -->
    <div class="modal fade" id="schemeModal" tabindex="-1" aria-labelledby="schemeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="schemeForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Scheme</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="schemeId">
                        <div class="mb-3">
                            <label class="form-label">Article</label>
                            <input type="text" class="form-control" id="schemeArticle" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scheme</label>
                            <select class="form-select" name="scheme" id="schemeSelect" required>
                                <option value="">Select Scheme</option>
                                <option value="Slicker Scheme">Slicker Scheme</option>
                                <option value="Vertex Scheme">Vertex Scheme</option>
                                <option value="Solea Scheme">Solea Scheme</option>
                                <option value="P-Toes Scheme">P-Toes Scheme</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function redirectData() {
            const limit = document.getElementById("hstack-limit").value;
            const order = document.getElementById("hstack-order").value;
            location.replace("articleV2.php?skip=0&limit=" + limit + "&order=" + order);
        }

        function openSchemeModal(id, article, currentScheme) {
            $("#schemeId").val(id);
            $("#schemeArticle").val(article);
            $("#schemeSelect").val(currentScheme);
            new bootstrap.Modal(document.getElementById('schemeModal')).show();
        }

        $("#schemeForm").submit(function(e) {
            e.preventDefault();
            const id = $("#schemeId").val();
            const scheme = $("#schemeSelect").val();
            $.post("update_scheme.php", {
                id: id,
                scheme: scheme
            }, function(response) {
                if (response.status === 'success') {
                    $("#scheme-" + id).text(scheme);
                    bootstrap.Modal.getInstance(document.getElementById('schemeModal')).hide();
                } else {
                    alert("Error: " + response.message);
                }
            }, 'json');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>