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
</head>

<body>
<?php include("src/navbar.php"); ?>
<div id="home"></div>

<div class="container">
    <?php

    include("src/db/database.php");
    include("src/minecraft/query.php");

    startQuery();

    $list = $connection->query("SELECT * FROM serverlist");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        $serverInfo = [
            "title" => $row["title"],
            "caption" => $row["caption"],
            "address" => $row["address"],
            "port" => $row["port"],

            "status" => '<span class="badge badge-danger">Offline</span>',
            "inList" => false,
            "players" => "0",
            "maxPlayers" => "0",
            "version" => "0.0.0"
        ];

        $query = $connection->query("SELECT * FROM querydata");
        $query->setFetchMode(PDO::FETCH_ASSOC);

        while (($rowQ = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            if ($rowQ["id"] === $row["id"]) {
                $serverInfo["status"] = '<span class="badge badge-success">Online</span>';
                $serverInfo["inList"] = true;
                $serverInfo["players"] = $rowQ["players"];
                $serverInfo["maxPlayers"] = $rowQ["maxplayers"];
                $serverInfo["version"] = $rowQ["version"];
                break;
            }
        }

        if (!$serverInfo["inList"]) {
            $queryResult = query($serverInfo["address"], $serverInfo["port"]);

            if ($queryResult !== null) {
                $serverInfo["status"] = '<span class="badge badge-success">Online</span>';
                $serverInfo["players"] = $queryResult["Players"];
                $serverInfo["maxPlayers"] = $queryResult["MaxPlayers"];
                $serverInfo["version"] = $queryResult["Version"];
            }
        }

        // Card image
        echo '<svg class="bd-placeholder-img card-img-top" width="100%" height="40" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" role="img"><img src="https://cdn.discordapp.com/attachments/833621011097845830/861064226637086750/crop.png" class="img-fluid rounded""></svg>';
        // Card
        echo '<div class="shadow p-3 mb-5 card mb-3 card-bg-dark"> 
                <div class="card-body card-bg-dark rounded">
                    <h3 class="card-title">' . $serverInfo["title"] . '</h3>
                    <h5><span class="badge bg-secondary">' . $serverInfo["address"] . ':' . $serverInfo["port"] . '</span> ' . $serverInfo["status"] . ' <span class="badge bg-primary">' . $serverInfo["version"] . '</span> <span class="badge bg-secondary">' . $serverInfo["players"] . '/' . $serverInfo["maxPlayers"] . '</span></h5>
                    <p class="card-text">' . $serverInfo["caption"] . '</p>
                </div></div>';
    }
    ?>
</div>
</body>
