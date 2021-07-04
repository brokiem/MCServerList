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
    if (strlen($name) >= 32) {
        header("location: failed.html");
        return;
    }

    if (strlen($caption) >= 128) {
        header("location: failed.html");
        return;
    }

    if (strlen($desc) >= 2048) {
        header("location: failed.html");
        return;
    }

    if (strlen($address) >= 64) {
        header("location: failed.html");
        return;
    }

    if (strlen($port) >= 8) {
        header("location: failed.html");
        return;
    }

    $query = query($address, (int)$port, 3);

    if (!$query) {
        header("location: failed.html");
        return;
    }

    addServer($name, $caption, $desc, $address, $port);
    header("location: success.html");
}

function addServer(string $name, string $caption, string $desc, string $address, $port) {
    include("src/db/database.php");
    $prep = $connection->prepare(
        "INSERT INTO serverlist (title, address, port, caption, description) 
        VALUES (:title, :address, :port, :caption, :description)
    ");

    $prep->bindParam(":title", $name);
    $prep->bindParam(":address", $address);
    $prep->bindParam(":port", $port);
    $prep->bindParam(":caption", $caption);
    $prep->bindParam(":description", $desc);

    $prep->execute();
}
