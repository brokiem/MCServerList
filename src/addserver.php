<?php

include("minecraft/query.php");

$input = $_POST;

$serverName = $input["serverName"];
$serverCaption = $input["serverCaption"];
$serverDesc = $input["serverDescription"];
$address = $input["serverAddress"];
$port = $input["serverPort"];

validate($serverName, $serverCaption, $serverDesc, $address, $port);

function validate(string $name, string $caption, string $desc, string $address, $port) {
    $query = query($address, (int)$port, 3);

    if (!$query) {
        header("location: failed.html");
        return;
    }

    addServer($name, $caption, $desc, $address, $port);
    header("location: success.html");
}

function addServer(string $name, string $caption, string $desc, string $address, $port) {
    include("db/database.php");
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
