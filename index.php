<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Server List</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <meta content="https://www-mcserverlist.herokuapp.com/" property="og:url"/>
    <meta content="MC Server List" property="og:title"/>
    <meta content="https://www-mcserverlist.herokuapp.com/assets/icon/icon.png" property="og:image"/>
    <meta content="Minecraft: Bedrock Edition Server List" property="og:description"/>
    <script>
        setTimeout(function () {
            window.location.href = "/server/list";
        }, 700);
    </script>
</head>

<body>
<div class="container">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <h1 class="display-3">Loading...</h1>
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"></div>
    </div>
</div>
</body>
</html>