<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Servers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">

    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>

    <script>
        $.ajax({
            type: 'GET',
            url: '/src/process/ShowServerProcess.php',
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
                    url: '/src/process/ShowServerProcess.php',
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

            setTimeout(function () {
                document.getElementById("showServersBtn").style.display = "block";
            }, 2500);
        });
    </script>
</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/src/Navbar.php"); ?>

<div id="space"></div>

<div class="container">
    <div class="servers" id="servers"></div>

    <div style="display:none;" id="showServersBtn" class="text-center">
        <button class="showServers btn btn-primary" type="button">Show more servers</button>
    </div>

    <div id="space"></div>
    <div id="space"></div>
</div>
</body>