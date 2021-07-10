<?php

$input = $_POST;

if (empty($input)) {
    header("location: /");
    die();
}

$secretKey = $input["secretKey"] ?? null;
$serverid = $input["serverId"] ?? null;

include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

if ($serverid === null or $secretKey !== $admin_secret_key) {
    echo "<script type='text/javascript'> window.history.go(-1); </script>";
    die();
}

$connection->prepare("DELETE FROM serverlist WHERE id = ?")->execute([$serverid]);
$connection->prepare("DELETE FROM querydata WHERE id = ?")->execute([$serverid]);

echo "<script type='text/javascript'> window.history.go(-1); </script>";