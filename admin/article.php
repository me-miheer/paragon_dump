<a?php
    require_once("checkLogin.php");
    require('../connection.php');
    header("Content-Type: text/html");
    $query=mysqli_query($mysql, 'SELECT * FROM app_settings' );

    ?>
    <!doctype html>
    <html lang="en">

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
        <div class="row">
            <div class="col-6" style="display: flex; justify-content: start; align-items: center;">
                <h1 class="p-0 m-0 text-dark"><b><u>Articles</u></b></h1>
            </div>
            <div class="col-6" style="display: flex; justify-content: end; align-items: center;"><a class="btn btn-sm btn-dark ps-4 pe-4" style="font-size: 18px;;">+</a></div>
        </div>

        <div class="row mt-5">
            <div class="col-6 text-start">
                <select name="" id="limit">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                </select>
            </div>
            <div class="col-6 text-end">
                <select name="" id="order">
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                </select>
            </div>
        </div>

        <div class="overflow-auto">
            <table class="table mt-3" id="myTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Article</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- List -->
                    <!-- List -->
                </tbody>
            </table>
        </div>

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
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>

    </html>