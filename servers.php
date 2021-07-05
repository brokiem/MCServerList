<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MC Servers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,500" rel="stylesheet">

    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="src/css/animate.css">
    <link rel="stylesheet" href="src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $.ajax({
            type: 'GET',
            url: 'src/servers.php',
            data: {
                'offset': $('.servers').length,
                'total': 5
            },
            success: function (data) {
                $('#servers').append(data);
                $('#loading').hide();
            }
        });

        window.onscroll = function () {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                $('#loading').show();
                $.ajax({
                    type: 'GET',
                    url: 'src/servers.php',
                    data: {
                        'offset': $('.servers').length,
                        'total': 5
                    },
                    success: function (data) {
                        $('#server').append(data);
                        $('#loading').hide();
                    }
                });
            }
        };
    </script>
</head>

<body>
<?php include("src/navbar.php"); ?>

<div id="home"></div>

<div class="container">
    <div class="servers" id="servers">

    </div>

    <?php
    include("src/cachemanager.php");
    startQuery();
    saveCachedFile();
    ?>

    <div id="loading">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <div class="spinner-border" role="status"></div>
        </div>
    </div>
</div>

<footer class="card-bg-dark text-center text-lg-start">
    <div class="text-center p-3">
        © 2021 Copyright <b>MC Server List</b>
    </div>
</footer>
</body>
