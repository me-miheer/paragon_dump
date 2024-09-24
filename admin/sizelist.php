<?php
require('../connection.php');
header("Content-Type: text/html");
$query = mysqli_query($mysql, 'SELECT * FROM app_settings');
$data = mysqli_fetch_assoc($query);

$query_fetch_tabular_data = mysqli_query($mysql, "SELECT * FROM item_sized ORDER BY KEYNAME DESC");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css");
    </style>
</head>

<body class="p-4" id="demo">
    <div class="row">
        <div class="col-6" style="display: flex; justify-content: start; align-items: center;">
            <h1 class="p-0 m-0 text-dark"><b><u>Size List</u></b></h1>
        </div>
        <div class="col-6" style="display: flex; justify-content: end; align-items: center;"><button class="btn btn-sm btn-dark ps-4 pe-4" style="font-size: 18px;;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus"></i></button></div>
    </div>

    <div class="row mt-3">
        <div class="input group">
            <input type="serach" class="form-select" id="myInput" placeholder="FILTER KEY">
        </div>
    </div>

    <div class="overflow-auto">
        <table class="table mt-3" id="myTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>KEY</th>
                    <th>SIZE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i1 = 1;
                while ($data1 = mysqli_fetch_assoc($query_fetch_tabular_data)) {
                    echo "<tr>";
                    echo "<td>" . $i1 . "</td>";
                    echo "<td>" . $data1['KEYNAME'] . "</td>";
                    echo "<td>" . $data1['SIZE'] . "</td>";
                ?>
                    <td> <a class='btn btn-danger' onclick="let access = prompt('Please type \'Yes\' to this Server'); if(access == 'Yes'){window.location.href = 'deleteSize.php?id=<?= $data1['ID'] ?>'}"><i class='bi bi-trash'></i><a></td>
                <?php
                    echo "</tr>";
                    $i1++;
                }
                ?>
                <!-- <tr>
                <td></td>
            </tr> -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <form action="createSize.php" method="POST">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">NEW SIZE</h1>
                        <a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="KEYNAME" placeholder="KEYNAME" class="form-control p-3" required>
                        <br>
                        <input type="text" name="SIZE" placeholder="SIZE" class="form-control p-3" required>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-success">CREATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>