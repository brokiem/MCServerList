<!DOCTYPE html>
<html lang="en">
<head>
    <title>MC Servers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <div class="text-center">
        <br><br><br>
        <h1 class="display-3">Loading...</h1>
        <p class="lead">Please wait..</p><br><br>
    </div>

    <script type="text/javascript">
        setTimeout(function () {
            window.location.href = "index";
            window.location.reload(true);
        }, 2000);
    </script>

    <?php
    include("src/cachemanager.php");
    startQuery();
    ?>
</head>
</html>