<?php

$connection = new PDO("mysql:host=127.0.0.1; dbname=serverlist", "root", "");
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