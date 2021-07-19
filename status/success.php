<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Server List</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="/assets/icon/favicon.ico" rel="icon" type="image/x-icon">
    <link href="/assets/css/animate.css" rel="stylesheet">
    <link href="/assets/css/styles.css" rel="stylesheet">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
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
<br>
<div class="container">
    <div class="shadow p-3 mb-5 card mb-3 card-bg-dark">
        <div class="card-body card-bg-dark rounded">
            <div class="text-center">
                <h1 class="display-3">Success!</h1>
                <p class="lead">Your server has been registered.</p><br>
                <?php
                if (isset($_GET["key"])) {
                    echo '<p class="lead">Save this server key to edit server<br><b>' . $_GET["key"] . '</b></p><br><br>';
                }
                ?>
                <p class="lead"><a class="btn btn-primary" href="/" role="button">Continue to homepage</a></p>
            </div>
        </div>
    </div>
</div>
</body>

</html>