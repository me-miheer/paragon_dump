<?php
require('../connection.php');
header("Content-Type: text/html");
$query = mysqli_query($mysql,'SELECT * FROM app_settings');
$data = mysqli_fetch_assoc($query);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css");</style>
    <style>
                /* Absolute Center Spinner */
        .loading {
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
            background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

        background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
        /* hide "loading..." text */
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
        }

        .loading:not(:required):after {
        content: '';
        display: block;
        font-size: 10px;
        width: 1em;
        height: 1em;
        margin-top: -0.5em;
        -webkit-animation: spinner 150ms infinite linear;
        -moz-animation: spinner 150ms infinite linear;
        -ms-animation: spinner 150ms infinite linear;
        -o-animation: spinner 150ms infinite linear;
        animation: spinner 150ms infinite linear;
        border-radius: 0.5em;
        -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
        box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        }
        @-moz-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        }
        @-o-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        }
        @keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        }
    </style>
  </head>
  <body class="p-4" id="demo">
    <div class="row pt-3 p-4">
        <div class="alert alert-secondary" role="alert">
            <strong>Be alert while editing </strong> these datas are very sensitive for the app.
          </div>
    </div>
    <form id="form-utf" action="javascript:void(0);" onsubmit="submitme()" method="POST">
        <div class="row p-0 m-0">
            <label for=""><h2><b>App Name</b></h2></label>
            <input type="text" class="form-control rounded-pill p-3 mt-2" value="<?=$data['app_name']?>" name="name" required>
            <label for="" class="mt-3"><h2><b>App Update</b></h2></label>
            <select class="form-select rounded-pill p-3 mt-2" name="app_update" required>
                <option value="no" <?=($data['app_update'] == 'no')?'selected':''?>>No</option>
                <option value="yes" <?=($data['app_update'] == 'yes')?'selected':''?>>Yes</option>
                <option value="false" <?=($data['app_update'] == 'false')?'selected':''?>>Out of service</option>
            </select>
            <label for="" class="mt-3"><h2><b>Update Required</b></h2></label>
            <select class="form-select rounded-pill p-3 mt-2" name="app_update_need" required>
                <option value="yes" <?=($data['app_update_need'] == 'yes')?'selected':''?>>Yes</option>
                <option value="no" <?=($data['app_update_need'] == 'no')?'selected':''?>>No</option>
            </select>
            <label for="" class="mt-3"><h2><b>App URL</b></h2></label>
            <input type="text" class="form-control rounded-pill p-3 mt-2" value="<?=$data['app_update_url']?>" name="app_update_url" required>
            <label for="" class="mt-3"><h2><b>App Version</b></h2></label>
            <input type="text" class="form-control rounded-pill p-3 mt-2" name="app_update_version" value="<?=$data['app_version']?>" required>
            <label for="" class="mt-3"><h2><b>Whats New</b></h2></label>
            <input type="text" class="form-control rounded-pill p-3 mt-2" name="whats_new" value="<?=$data['whats_new']?>" required>
            <button type="submit" class="rounded-pill p-4 mt-5 btn btn-dark" style="width: 100%;">Submit</button>
        </div>
    </form>
    <br><br><br>
    <div class="loading" id="loading" style="display: none;">Loading&#8230;</div>
    <div class="content"><h3></h3></div>
    <script>
        function submitme(){
            document.getElementById('loading').style.display = 'block';
            let data = $('#form-utf').serialize();
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("demo").innerHTML = this.responseText;
                }
            xhttp.open("POST", "submitsetting.php");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(data);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>