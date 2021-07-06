<?php

$mysql_address = getenv("mysql-address");
$mysql_db = getenv("mysql-db");
$mysql_username = getenv("mysql-user");
$mysql_password = getenv("mysql-pass");

$connection = new PDO("mysql:host=$mysql_address; dbname=$mysql_db", $mysql_username, $mysql_password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);