<?php

include("src/query/query.php");

$input = $_POST;

$serverName = $input["serverName"];
$serverCaption = $input["serverCaption"];
$serverDesc = $input["serverDescription"];
$address = $input["serverAddress"];
$port = $input["serverPort"];

validate($serverName, $serverCaption, $serverDesc, $address, $port);

function validate(string $name, string $caption, string $desc, string $address, $port) {
    $name = htmlspecialchars($name, ENT_COMPAT, 'ISO-8859-1');
    $caption = htmlspecialchars($caption, ENT_COMPAT, 'ISO-8859-1');
    $desc = htmlspecialchars($desc, ENT_COMPAT, 'ISO-8859-1');
    $address = htmlspecialchars($address, ENT_COMPAT, 'ISO-8859-1');
    $port = htmlspecialchars($port, ENT_COMPAT, 'ISO-8859-1');

    if (strlen($name) >= 32 or strlen($caption) >= 128 or strlen($desc) >= 2048 or strlen($address) >= 64 or strlen($port) >= 8) {
        header("location: failed");
        return;
    }

    $query = query($address, (int)$port, 3);

    if (!$query) {
        header("location: failed");
        return;
    }

    addServer($name, $caption, $desc, $address, (int)$port, $query);
    header("location: success");
}

function addServer(string $name, string $caption, string $desc, string $address, $port, $query) {
    include("src/db/database.php");
    $prep = $connection->prepare(
        "INSERT INTO serverlist (title, address, port, caption, description) 
        VALUES (:title, :address, :port, :caption, :description)
    ");

    $prep->bindParam(":title", $name, PDO::PARAM_STR);
    $prep->bindParam(":address", $address, PDO::PARAM_STR);
    $prep->bindParam(":port", $port, PDO::PARAM_INT);
    $prep->bindParam(":caption", $caption, PDO::PARAM_STR);
    $prep->bindParam(":description", $desc, PDO::PARAM_STR);

    $prep->execute();

    $serversFile = @file_get_contents("cache/servers.json");
    if (!$serversFile) {
        $servers = json_decode($serversFile, true);

        $servers[mt_rand(5000, 50000)] = [
            "title" => $row["title"],
            "caption" => $row["caption"],
            "address" => $row["address"],
            "port" => $row["port"],
            "status" => "online",
            "players" => $query["Players"] ?? 0,
            "maxPlayers" => $query["MaxPlayers"] ?? 0,
            "version" => $query["Version"] ?? "0.0.0"
        ];
        file_put_contents("cache/servers.json", json_encode($servers));
    }
}
