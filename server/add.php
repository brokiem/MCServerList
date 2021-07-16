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
    <meta content="https://www-mcserverlist.herokuapp.com/" property="og:url"/>
    <meta content="MC Server List" property="og:title"/>
    <meta content="https://www-mcserverlist.herokuapp.com/assets/icon/icon.png" property="og:image"/>
    <meta content="Minecraft: Bedrock Edition Servers List" property="og:description"/>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
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
                <a class="nav-link effect-underline" aria-current="page" href="/server/list">Home</a>
                <a class="nav-link active effect-underline" href="">Add Server</a>
            </div>
        </div>
    </div>
</nav>

<div id="space"></div>
<div id="space"></div>

<div class="container">
    <form id="addserver-form" method="post" action="/src/process/AddServerProcess.php">
        <div class="mb-4">
            <label for="serverName" class="form-label">Server name *</label>
            <input type="text" class="form-control" name="serverName" minlength="3" maxlength="32" required>
        </div>
        <div class="mb-4">
            <label for="serverCaption" class="form-label">Server caption *</label>
            <input type="text" class="form-control" name="serverCaption" minlength="10" maxlength="128" required>
        </div>
        <div class="mb-3">
            <label for="serverDescription" class="form-label">Server description (Optional)</label>
            <textarea class="form-control" name="serverDescription" rows="3" maxlength="2048"></textarea>
        </div>
        <div class="mb-4">
            <label for="serverBanner" class="form-label">Raw PNG URL server banner (Optional)</label>
            <textarea type="text" class="form-control" name="serverBanner" maxlength="1024"></textarea>
        </div>
        <div id="space"></div>
        <label for="serverInfo" class="form-label">Server address and port *</label>
        <div class="input-group mb-4">
            <input type="text" class="form-control" placeholder="Address" name="serverAddress" minlength="3"
                   maxlength="64" required>
            <span class="input-group-text">::</span>
            <input type="number" class="form-control" placeholder="Port" name="serverPort" value="19132" maxlength="8"
                   min="1" max="65535" minlength="1" data-bind="value:replyNumber" required>
        </div>
        <div id="space"></div>
        <label for="serverInfo" class="form-label">Verify that you are not a robot *</label>
        <div class="form-group">
            <div class="g-recaptcha" data-theme="dark" data-sitekey="6LcLm30bAAAAAAd6_l0PbFdzzLK6TCjrVjYd0JiL"></div>
        </div>
        <div id="space"></div>
        <button id="sumbitBtn" type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div id="space"></div>
    <div id="space"></div>
    <div id="space"></div>
</div>
</body>