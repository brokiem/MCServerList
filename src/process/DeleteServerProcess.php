<?php

$input = $_POST;

if (empty($input)) {
    header("location: /");
    return;
}

$secretKey = $input["secretKey"] ?? null;
$serverid = $input["serverId"] ?? null;

include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

if ($serverid === null or $secretKey !== $admin_secret_key) {
    echo "<script type='text/javascript'> window.history.go(-1); </script>";
    return;
}

$connection->exec("DELETE FROM serverlist WHERE id=" . $serverid);
$connection->exec("DELETE FROM querydata WHERE id=" . $serverid);

echo "<script type='text/javascript'> window.history.go(-1); </script>";