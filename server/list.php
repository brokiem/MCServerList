<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Servers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand:300,400">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/minecraftia" type="text/css"/>
    <link rel="icon" href="/assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/minecraftcolors.min.css">
    <meta content="https://www-mcserverlist.herokuapp.com/" property="og:url"/>
    <meta content="MC Server List" property="og:title"/>
    <meta content="https://www-mcserverlist.herokuapp.com/assets/icon/icon.png" property="og:image"/>
    <meta content="Minecraft: Bedrock Edition Servers List" property="og:description"/>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery.min.js"></script>
    <script>
        $.ajax({
            type: 'GET',
            url: '/src/process/ShowServerProcess.php',
            data: {
                'offset': $('.servers').length,
                'total': 10
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
                            $('#servers').append($(data).hide().fadeIn(800));
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

            setTimeout(function () {
                document.getElementById("footerCard").style.display = "block";
            }, 700);
        });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-F0YD0SXLV4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-F0YD0SXLV4');
    </script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand zoom-in" href="/"><b><img src="/assets/icon/icon.png" alt="" width="30" height="30"
                                                         class="d-inline-block align-text-top"> MC Servers Beta</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active effect-underline" aria-current="page" href="/server/list">Home</a>
                <a class="nav-link effect-underline" href="/server/add">Add Server</a>
            </div>
        </div>
    </div>
</nav>

<div id="space"></div>
<div id="space"></div>
<div class="container">
    <div class="servers" id="servers"></div>
    <div style="display:none;" id="showServersBtn" class="text-center d-grid gap-2">
        <button class="showServers btn btn-primary" type="button">Show more servers</button>
    </div>
    <div id="space"></div>
    <div id="space"></div>
    <div id="space"></div>
</div>
</body>
<footer style="display:none;" id="footerCard" class="shadow-lg footer-color text-center text-lg-start">
    <div class="text-center p-3">Copyright © 2021 <a href="https://github.com/brokiem"
                                                     target="_blank"><b>brokiem</b></a></div>
</footer>