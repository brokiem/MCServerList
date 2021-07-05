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
                $(".showServers").attr("original-text", $(".showServers").html()).prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')

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

        setTimeout(function () {
            document.getElementById("showServersBtn").style.display = "block";
        }, 5000);
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
    ?>

    <div style="display:none;" id="showServersBtn" class="text-center">
        <button class="showServers btn btn-primary" type="button">Show more servers</button>
    </div>

    <div id="home"></div>
    <div id="home"></div>
</div>
</body>