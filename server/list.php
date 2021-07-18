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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-F0YD0SXLV4"></script>
    <script>
        $.ajax({
            type: 'GET',
            url: '/src/process/ShowServerProcess.php',
            data: {
                'type': 1,
                'offset': $('.servers').length,
                'total': 4
            },
            success: function (data) {
                if (false !== data) {
                    $('#servers').append(data);
                }
            }
        });

        $.ajax({
            type: 'GET',
            url: '/src/process/ShowServerProcess.php',
            data: {
                'type': 0,
                'offset': $('.new-servers').length,
                'total': 4
            },
            success: function (data) {
                if (false !== data) {
                    $('#new-servers').append(data);
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
                        'type': 1,
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

            $(".showNewServers").click(function () {
                $(".showNewServers").attr("original-text", $(".showNewServers").html()).prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
                $.ajax({
                    type: 'GET',
                    url: '/src/process/ShowServerProcess.php',
                    data: {
                        'type': 0,
                        'offset': $('.new-servers').length,
                        'total': 5
                    },
                    success: function (data) {
                        if (false !== data) {
                            $('#new-servers').append($(data).hide().fadeIn(800));

                            setTimeout(function () {
                                $(".showNewServers").prop("disabled", false).html($(".showNewServers").attr("original-text"));
                            }, 2000);
                        }
                    }
                });
            });

            setTimeout(function () {
                document.getElementById("moreBtn").style.display = "block";
                document.getElementById("newBtn").style.display = "block";
            }, 2500);

            setTimeout(function () {
                document.getElementById("footerCard").style.display = "block";
            }, 700);
        });

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
        <a class="navbar-brand zoom-in px-5" href="/"><b><img src="/assets/icon/icon.png" alt="" width="30" height="30"
                                                              class="d-inline-block align-text-top"> MC Servers Beta</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto flex-nowrap px-5">
                <a class="nav-link active effect-underline" aria-current="page" href="/server/list">Home</a>
                <a class="nav-link effect-underline" href="/server/add">Add Server</a>
                <a class="nav-link effect-underline" href="/server/edit">Edit Server</a>
            </div>
        </div>
    </div>
</nav>

<br>
<br>

<div class="container">
    <?php
    if (isset($_GET["new"])) {
        echo '<p style="font-size:25px"><svg width="35" height="35" fill="currentColor" class="bi bi-list-ul" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg><b>  New Servers</b></p>
        <br>

        <div class="new-servers" id="new-servers"></div>
        <div style="display:none;" id="showNewServersBtn" class="text-center d-grid gap-2">
        <button class="showNewServers btn btn-primary btn-block text-uppercase mb-2 shadow-sm" type="button">Show more servers</button>
        </div><br><br></div></body>';
        goto footer;
    }

    if (isset($_GET["more"])) {
        echo '<p style="font-size:25px"><svg width="35" height="35" fill="currentColor" class="bi bi-list-task" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5H2zM3 3H2v1h1V3z"/><path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9z"/><path fill-rule="evenodd" d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V7zM2 7h1v1H2V7zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5H2zm1 .5H2v1h1v-1z"/></svg><b>  More Servers</b></p>
        <br>

        <div class="servers" id="servers"></div>
        <div style="display:none;" id="showServersBtn" class="text-center d-grid gap-2">
        <button class="showServers btn btn-primary btn-block text-uppercase mb-2 shadow-sm" type="button">Show more servers</button>
        </div><br><br></div></body>';
        goto footer;
    }
    ?>

    <p style="font-size:25px">
        <svg width="35" height="35" fill="currentColor" class="bi bi-list-ul" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
        </svg>
        <b> New Servers</b></p>

    <br>

    <div class="new-servers" id="new-servers"></div>
    <div style="display:none;" id="newBtn" class="text-center d-grid gap-2">
        <a href="/server/list?new" class="btn btn-primary btn-block text-uppercase mb-2 shadow-sm" type="button">Show
            more new servers</a>
    </div>

    <br>
    <br>
    <br>

    <p style="font-size:25px">
        <svg width="35" height="35" fill="currentColor" class="bi bi-list-task" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5H2zM3 3H2v1h1V3z"/>
            <path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9z"/>
            <path fill-rule="evenodd"
                  d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V7zM2 7h1v1H2V7zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5H2zm1 .5H2v1h1v-1z"/>
        </svg>
        <b> More Servers</b></p>

    <br>

    <div class="servers" id="servers"></div>
    <div style="display:none;" id="moreBtn" class="text-center d-grid gap-2">
        <a href="/server/list?more" class="btn btn-primary btn-block text-uppercase mb-2 shadow-sm" type="button">Show
            more servers</a>
    </div>

    <br>
    <br>
</div>
</body>
<?php footer: ?>
<footer style="display:none;" id="footerCard" class="shadow-lg footer-color text-center text-lg-start">
    <div class="text-center p-3">Copyright © 2021 <a href="https://github.com/brokiem"
                                                     target="_blank"><b>brokiem</b></a></div>
</footer>