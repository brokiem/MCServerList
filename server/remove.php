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
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand zoom-in" href="/"><b>MC Servers Beta</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link zoom-in" aria-current="page" href="/">Home</a>
                <a class="nav-link zoom-in" href="/server/add">Add Server</a>
                <a class="nav-link zoom-in" href="/server/edit">Edit Server</a>
            </div>
        </div>
    </div>
</nav>

<br>
<br>

<div class="container text-center text-lg-start">
    <form id="search-server-form" method="post" action="/src/process/SearchServerProcess.php">
        <div class="input-group-text transparent">
            <input type="text" class="form-control" placeholder="Server address" name="serverAddress" maxlength="64"
                   required>
            <span class="input-group-text rounded-0 card-bg-dark">/</span>
            <input type="text" class="form-control" placeholder="Secret key" name="secretKey" maxlength="32" required>

            <button id="search-button" class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <br>

    <form id="delete-server-form" method="post" action="/src/process/DeleteServerProcess.php">
        <div class="input-group-text transparent">
            <input type="text" class="form-control" placeholder="Server id" name="serverId" maxlength="64" required>
            <span class="input-group-text rounded-0 card-bg-dark">/</span>
            <input type="text" class="form-control" placeholder="Secret key" name="secretKey" maxlength="32" required>

            <button id="search-button" class="btn btn-danger" type="submit">Delete</button>
        </div>
    </form>
</div>

</body>