<!DOCTYPE html>
<html lang="en">

<head>
    <title>MC Servers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Work Sans">

    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<?php include("src/navbar.php"); ?>
<div id="home"></div>

<div class="container">
    <?php

    include("src/db/database.php");
    include("src/query/query.php");

    startQuery();
    saveCachedFile();

    $serversFile = @file_get_contents("cache/servers.json");

    if (!$serversFile) {
        $servers = json_decode($serversFile, true);

        foreach ($servers as $id => $data) {
            // Card image
            echo '<svg class="bd-placeholder-img card-img-top" width="100%" height="40" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" role="img"><img src="https://cdn.discordapp.com/attachments/833621011097845830/861064226637086750/crop.png" class="img-fluid rounded""></svg>';
            // Card
            echo '<div class="shadow p-3 mb-5 card mb-3 card-bg-dark"> 
                <div class="card-body card-bg-dark rounded">
                    <h3 class="card-title">' . $data["title"] . '</h3>
                    <h5><span class="badge bg-secondary">' . $data["address"] . ':' . $data["port"] . '</span> ' . $data["status"] . ' <span class="badge bg-primary">' . $data["version"] . '</span> <span class="badge bg-info">' . $data["players"] . '/' . $data["maxPlayers"] . '</span></h5>
                    <p class="card-text">' . $data["caption"] . '</p>
                </div></div>';
        }
    }
    ?>
</div>
</body>
