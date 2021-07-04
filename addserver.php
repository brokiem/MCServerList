<!DOCTYPE html>
<html lang="en">

<head>
    <title>MC Servers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Work Sans">

    <link rel="stylesheet" href="src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<?php include("src/navbar.php"); ?>
<div id="home"></div>

<div class="container">
    <form id="addserver-form" method="post" action="addserver-proc.php">
        <div class="mb-4">
            <label for="serverName" class="form-label">Server name</label>
            <input type="text" class="form-control" name="serverName" minlength="3" maxlength="32" required>
        </div>

        <div class="mb-4">
            <label for="serverCaption" class="form-label">Server caption</label>
            <input type="text" class="form-control" name="serverCaption" minlength="10" maxlength="128" required>
        </div>

        <div class="mb-3">
            <label for="serverDescription" class="form-label">Server description</label>
            <textarea class="form-control" name="serverDescription" rows="3" maxlength="2048" required></textarea>
        </div>
        <br>
        <label for="serverInfo" class="form-label">Server address and port</label>
        <div class="input-group mb-4">
            <input type="text" class="form-control" placeholder="Address" name="serverAddress" minlength="3"
                   maxlength="64" required>
            <span class="input-group-text">::</span>
            <input type="number" class="form-control" placeholder="Port" name="serverPort" value="19132" maxlength="8"
                   min="1"
                   max="65535" minlength="1" data-bind="value:replyNumber" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>