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

</head>

<body>
    <div class="section" style="position: fixed; background-color:aquamarine; width: 100vw; height: 100vh; margin: 0; padding: 0; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items:center;">
        <div class="data">
            <video width="156" height="156" preload="none" style="background: transparent  url('https://cdn-icons-png.flaticon.com/512/8629/8629417.png') 50% 50% / fit no-repeat; border-radius: 50%; overflow: hidden;" autoplay="autoplay" loop="true" muted="muted" playsinline="">
                <source src="https://cdn-icons-mp4.flaticon.com/512/8629/8629417.mp4" type="video/mp4">
            </video>
            <br>
            <div class="progress mt-3" role="progressbar" id="progessBar" aria-label="Warning example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div id="progressVal" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(async function() {
            let i = 0;

            // Function to wrap setTimeout in a promise
            function delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            function delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            while (i <= 100) {
                $("#progressBar").attr("aria-valuenow", i);
                $("#progressVal").html(i + "%");
                $("#progressVal").css("width", i+"%");
                await delay(30);
                if(i == 99){
                    console.log("yess");
                    location.replace("article.php");
                    exit;
                }
                i++;
            }
        });
    </script>
</body>

</html>