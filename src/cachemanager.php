<?php

/** @noinspection MkdirRaceConditionInspection */
function checkLastCached($putContent = true): bool {
    if (!is_dir("cache")) {
        mkdir("cache");
    }

    $file = @file_get_contents("cache/lastExec.json");

    if (!$file) {
        if ($putContent) {
            file_put_contents("cache/lastExec.json", json_encode(["lastMysqlCache" => microtime(true)]));
        }
        return true;
    }

    if (!isset(json_decode($file, true)["lastMysqlCache"])) {
        if ($putContent) {
            file_put_contents("cache/lastExec.json", json_encode(array_merge(json_decode($file, true), ["lastMysqlCache" => microtime(true)])));
        }
        return true;
    }

    if ((120.0 + (float)json_decode($file, true)["lastMysqlCache"]) < microtime(true)) {
        if ($putContent) {
            file_put_contents("cache/lastExec.json", json_encode(array_merge(json_decode($file, true), ["lastMysqlCache" => microtime(true)])));
        }
        return true;
    }

    return false;
}

function saveCachedFile(bool $force = false): bool {
    if (!$force && !checkLastCached()) {
        return false;
    }

    include("db/database.php");

    $list = $connection->query("SELECT * FROM serverlist");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    $query = $connection->query("SELECT * FROM querydata");
    $query->setFetchMode(PDO::FETCH_ASSOC);

    $servers = [];
    $queryData = [];

    while (($rowQ = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        $queryData[$rowQ["id"]] = [
            "status" => $rowQ["status"],
            "players" => $rowQ["players"],
            "maxplayers" => $rowQ["maxplayers"],
            "version", $rowQ["version"]
        ];
    }

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        $servers[$row["id"]] = [
            "title" => $row["title"],
            "caption" => $row["caption"],
            "address" => $row["address"],
            "port" => $row["port"],
        ];

        foreach ($queryData as $id => $data) {
            if ($id === $row["id"]) {
                $servers[$id]["status"] = $data["status"];
                $servers[$id]["players"] = $data["players"];
                $servers[$id]["maxPlayers"] = $data["maxplayers"];
                $servers[$id]["version"] = $data["version"];
            }
        }
    }

    file_put_contents("cache/servers.json", json_encode($servers));
    return true;
}

/** @noinspection MkdirRaceConditionInspection */
function checkLastQuery($putContent = true): bool {
    if (!is_dir("cache")) {
        mkdir("cache");
    }

    $file = @file_get_contents("cache/lastExec.json");

    if (!$file) {
        file_put_contents("cache/lastExec.json", json_encode(["lastQuery" => microtime(true)]));
        return true;
    }

    if (!isset(json_decode($file, true)["lastQuery"])) {
        file_put_contents("cache/lastExec.json", json_encode(array_merge(json_decode($file, true), ["lastQuery" => microtime(true)])));
        return true;
    }

    if ((300.0 + (float)json_decode($file, true)["lastQuery"]) < microtime(true)) {
        file_put_contents("cache/lastExec.json", json_encode(array_merge(json_decode($file, true), ["lastQuery" => microtime(true)])));
        return true;
    }

    return false;
}

function startQuery(bool $force = false) {
    if (!$force && !checkLastQuery()) {
        return;
    }

    include("src/db/database.php");
    include("src/query/query.php");

    $list = $connection->query("SELECT * FROM serverlist");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    $q = $connection->query("SELECT * FROM querydata");
    $q->setFetchMode(PDO::FETCH_ASSOC);

    while (($rowQ = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        $queryData[$rowQ["id"]] = [
            "status" => $rowQ["status"],
            "players" => $rowQ["players"],
            "maxplayers" => $rowQ["maxplayers"],
            "version", $rowQ["version"]
        ];
    }

    $queryRes = [];

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        $id = $row["id"];
        $address = $row["address"];
        $port = $row["port"];

        $query = query($address, $port);

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
            foreach ($queryData as $id => $data) {
                if ($id === $row["id"]) {
                    $queryRes[$id] = [
                        "id" => $id,
                        "status" => "offline",
                        "players" => "0",
                        "maxplayers" => $data["maxplayers"],
                        "version" => $data["version"],
                        "hostname" => "null"
                    ];
                    break;
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
}