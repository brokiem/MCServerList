<?php

start(init());

function init(): PDO {
    $mysql_address = getenv("mysql-address-query");
    $mysql_db = getenv("mysql-db-query");
    $mysql_username = getenv("mysql-user-query");
    $mysql_password = getenv("mysql-pass-query");

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
        banner VARCHAR(1024) NOT NULL,
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

    return $connection;
}

function start(PDO $connection): void {
    query: // loop
    echo "Query server...\n";

    $list = $connection->query("SELECT * FROM serverlist");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    $queryRes = [];

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        $id = $row["id"];
        $address = $row["address"];
        $port = $row["port"];

        $query = query($address, $port, 3);

        if ($query !== null) {
            $queryRes[$id] = [
                "id" => $id,
                "status" => "online",
                "players" => $query["Players"],
                "maxplayers" => $query["MaxPlayers"],
                "version" => $query["Version"],
                "hostname" => $query["HostName"]
            ];
        } else {
            $q = $connection->query("SELECT * FROM querydata");
            $q->setFetchMode(PDO::FETCH_ASSOC);

            while (($rowQ = $q->fetch(PDO::FETCH_ASSOC)) !== false) {
                if ($id === $rowQ["id"]) {
                    $queryRes[$id] = [
                        "id" => $id,
                        "status" => "offline",
                        "players" => "0",
                        "maxplayers" => $rowQ["maxplayers"],
                        "version" => $rowQ["version"],
                        "hostname" => $rowQ["hostname"]
                    ];
                }
            }
        }
    }

    foreach ($queryRes as $array) {
        $connection->prepare("INSERT INTO querydata (id, status, players, maxplayers, version, hostname) 
                VALUES (:id, :status, :players, :maxplayers, :version, :hostname)
                ON DUPLICATE KEY UPDATE id=VALUES(id), status=VALUES(status), players=VALUES(players), version=VALUES(version), hostname=VALUES(hostname)"
        )->execute($array);
    }

    echo "Query process done\n";
    sleep(150); // 150 seconds
    goto query; // loop
}

// grabbed from https://github.com/jasonwynn10/libpmquery/blob/master/src/libpmquery/PMQuery.php
function query(string $host, int $port, int $timeout = 4): ?array {
    $socket = @fsockopen('udp://' . $host, $port, $errno, $errstr, $timeout);

    if ($errno and $socket !== false) {
        fclose($socket);
        //throw new \RuntimeException($errstr, $errno);
        return null;
    }

    if ($socket === false) {
        //throw new \RuntimeException($errstr, $errno);
        return null;
    }

    stream_set_timeout($socket, $timeout);
    stream_set_blocking($socket, true);

    // hardcoded magic https://github.com/facebookarchive/RakNet/blob/1a169895a900c9fc4841c556e16514182b75faf8/Source/RakPeer.cpp#L135
    $OFFLINE_MESSAGE_DATA_ID = \pack('c*', 0x00, 0xFF, 0xFF, 0x00, 0xFE, 0xFE, 0xFE, 0xFE, 0xFD, 0xFD, 0xFD, 0xFD, 0x12, 0x34, 0x56, 0x78);
    $command = \pack('cQ', 0x01, time()); // DefaultMessageIDTypes::ID_UNCONNECTED_PING + 64bit current time
    $command .= $OFFLINE_MESSAGE_DATA_ID;
    $command .= \pack('Q', 2); // 64bit guid
    $length = \strlen($command);

    if ($length !== fwrite($socket, $command, $length)) {
        return null;
    }

    $data = fread($socket, 4096);

    fclose($socket);

    if (empty($data) or $data === false) {
        //throw new \RuntimeException("Server failed to respond", E_WARNING);
        return null;
    }

    if ($data[0] !== "\x1C") {
        //throw new \RuntimeException("First byte is not ID_UNCONNECTED_PONG.", E_WARNING);
        return null;
    }

    if (substr($data, 17, 16) !== $OFFLINE_MESSAGE_DATA_ID) {
        //throw new \RuntimeException("Magic bytes do not match.");
        return null;
    }

    // TODO: What are the 2 bytes after the magic?
    $data = \substr($data, 35);

    // TODO: If server-name contains a ';' it is not escaped, and will break this parsing
    $data = \explode(';', $data);

    return [
        'GameName' => $data[0] ?? null,
        'HostName' => $data[1] ?? null,
        'Protocol' => $data[2] ?? null,
        'Version' => $data[3] ?? null,
        'Players' => $data[4] ?? null,
        'MaxPlayers' => $data[5] ?? null,
        'ServerId' => $data[6] ?? null,
        'Map' => $data[7] ?? null,
        'GameMode' => $data[8] ?? null,
        'NintendoLimited' => $data[9] ?? null,
        'IPv4Port' => $data[10] ?? null,
        'IPv6Port' => $data[11] ?? null,
        'Extra' => $data[12] ?? null, // TODO: What's in this?
    ];
}