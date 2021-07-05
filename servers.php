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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
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
                if (false !== data) {
                    $('#servers').append(data);
                }
            }
        });

        $(document).ready(function () {
            $(".showServers").click(function () {
                $(".showServers").attr("original-text", $(".showServers").html()).prop("disabled", true).html('<div class="d-grid gap-2 d-flex"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>')

                $.ajax({
                    type: 'GET',
                    url: 'src/servers.php',
                    data: {
                        'offset': $('.servers').length,
                        'total': 5
                    },
                    success: function (data) {
                        if (false !== data) {
                            $('#servers').append(data);
                            setTimeout(function () {
                                $(".showServers").prop("disabled", false).html($(".showServers").attr("original-text"));
                            }, 2000);
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>
<?php include("src/navbar.php"); ?>

<div id="home"></div>

<div class="container">
    <div class="servers" id="servers"></div>

    <?php
    include("src/cachemanager.php");
    startQuery();
    saveCachedFile();
    ?>

    <div id="home"></div>
    <div id="home"></div>
</div>

<footer class="card-bg-dark text-center text-lg-start">
    <div class="text-center p-3">
        © 2021 Copyright <b>MC Server List</b>
    </div>
</footer>
</body>