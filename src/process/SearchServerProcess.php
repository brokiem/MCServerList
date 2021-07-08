<?php

$input = $_POST;

if (empty($input)) {
    header("location: /");
    die();
}

$secretKey = $input["secretKey"] ?? null;
$serverAddress = $input["serverAddress"] ?? null;

include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

if ($serverAddress === null or $secretKey !== $admin_secret_key) {
    echo "<script type='text/javascript'> window.history.go(-1); </script>";
    die();
}

$query = $connection->query("SELECT * FROM serverlist");
$query->setFetchMode(PDO::FETCH_ASSOC);

while (($row = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
    if ($row["address"] === $serverAddress) {
        $address = $row["address"];
        $id = $row["id"];
        $port = $row["port"];
        echo "$address:$port = $id ";
        break;
    }
}