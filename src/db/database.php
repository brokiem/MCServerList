<?php

$mysql_address = getenv("mysql-address");
$mysql_db = getenv("mysql-db");
$mysql_username = getenv("mysql-user");
$mysql_password = getenv("mysql-pass");

$connection = new PDO("mysql:host=$mysql_address; dbname=$mysql_db", $mysql_username, $mysql_password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$connection->exec("
CREATE TABLE IF NOT EXISTS serverlist (
    id INT UNSIGNED AUTO_INCREMENT,
    title VARCHAR(32) NOT NULL,
    address VARCHAR(64) NOT NULL,
    port INT(8) NOT NULL,
    caption VARCHAR(128) NOT NULL,
    description VARCHAR(2048) NOT NULL,
    PRIMARY KEY (id)
)");
$connection->exec("
CREATE TABLE IF NOT EXISTS querydata (
    id INT UNSIGNED NOT NULL,
    status VARCHAR(16) NOT NULL,
    players CHAR(32) NOT NULL,
    maxplayers CHAR(32) NOT NULL,
    version CHAR(16) NOT NULL,
    hostname VARCHAR(512) NOT NULL,
    PRIMARY KEY (id)
)");

/** @noinspection MkdirRaceConditionInspection */
function checkLastCached(): bool {
    if (!is_dir("cache")) {
        mkdir("cache");
    }

    $file = @file_get_contents("cache/lastExec.json");

    if (!$file) {
        file_put_contents("cache/lastExec.json", json_encode(["lastMysqlCache" => microtime(true)]));
    } else if ((120.0 + (float)json_decode($file, true)["lastQuery"]) < microtime(true)) {
        file_put_contents("cache/lastExec.json", json_encode(["lastMysqlCache" => microtime(true)]));
        return true;
    }

    return false;
}

function saveCachedFile(bool $force = false): void {
    if (!$force && !checkLastCached()) {
        return;
    }

    include("database.php");

    $list = $connection->query("SELECT * FROM serverlist");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    $servers = [];

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        $servers[$row["id"]] = [
            "title" => $row["title"],
            "caption" => $row["caption"],
            "address" => $row["address"],
            "port" => $row["port"],
        ];
    }

    file_put_contents("cache/servers.json", json_encode($servers));
}