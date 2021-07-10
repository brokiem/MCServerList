<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Servers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand:300,400">
    <link rel="icon" href="/assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/minecraftcolors.min.css">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/src/Navbar.php"); ?>

<div id="space"></div>
<div id="space"></div>

<div class="container text-center text-lg-start">
    <form id="search-server-form" method="post" action="/src/process/SearchServerProcess.php">
        <div class="input-group-append">
            <input type="text" class="form-control" placeholder="Server address" name="serverAddress" maxlength="64"
                   required>
            <span class="input-group-text rounded-0 card-bg-dark">/</span>
            <input type="text" class="form-control" placeholder="Secret key" name="secretKey" maxlength="32" required>

            <div class="input-group-append">
                <button id="search-button" class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <div id="space"></div>

    <form id="delete-server-form" method="post" action="/src/process/DeleteServerProcess.php">
        <div class="input-group-append">
            <input type="text" class="form-control" placeholder="Server id" name="serverId" maxlength="64" required>
            <span class="input-group-text rounded-0 card-bg-dark">/</span>
            <input type="text" class="form-control" placeholder="Secret key" name="secretKey" maxlength="32" required>

            <div class="input-group-append">
                <button id="search-button" class="btn btn-danger" type="submit">Delete</button>
            </div>
        </div>
    </form>
</div>

</body>