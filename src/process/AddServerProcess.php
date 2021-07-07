<?php

$input = $_POST;

if (empty($input)) {
    header("location: /");
    return;
}

$captchaRes = $input['g-recaptcha-response'] ?? null;
$serverName = $input["serverName"];
$serverCaption = $input["serverCaption"] ?? "";
$serverDesc = $input["serverDescription"];
$address = $input["serverAddress"];
$port = $input["serverPort"];

validate($captchaRes, $serverName, $serverCaption, $serverDesc, $address, $port);

function validate($captcha, string $name, string $caption, string $desc, string $address, $port) {
    include($_SERVER['DOCUMENT_ROOT'] . "/src/db/Database.php");

    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    $name = htmlspecialchars($name, ENT_COMPAT, 'ISO-8859-1');
    $caption = htmlspecialchars($caption, ENT_COMPAT, 'ISO-8859-1');
    $desc = htmlspecialchars($desc, ENT_COMPAT, 'ISO-8859-1');
    $address = htmlspecialchars($address, ENT_COMPAT, 'ISO-8859-1');
    $port = htmlspecialchars($port, ENT_COMPAT, 'ISO-8859-1');

    if ($captcha == null || $caption == "") {
        header("location: /status/captcha.html");
        return;
    }

    $recaptcha = new ReCaptcha\ReCaptcha($captcha_secret_key);
    $response = $recaptcha->verify($captcha, $_SERVER['REMOTE_ADDR']);

    if (!$response->isSuccess()) {
        header("location: /status/captcha.html");
        return;
    }

    if (strlen($name) >= 32 or strlen($caption) >= 128 or strlen($desc) >= 2048 or strlen($address) >= 64 or strlen($port) >= 8) {
        header("location: /status/failed");
        return;
    }

    $list = $connection->query("SELECT * FROM serverlist WHERE address IN (SELECT address FROM serverlist WHERE address = '$address')");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    if (is_array($row = $list->fetch(PDO::FETCH_ASSOC)) && (int)$row["port"] === (int)$port) {
        header("location: /status/failed");
        return;
    }

    include($_SERVER['DOCUMENT_ROOT'] . "/src/query/Query.php");
    $query = query($address, (int)$port, 3);

    if (!$query) {
        header("location: /status/failed");
        return;
    }

    addServer($name, $caption, $desc, $address, (int)$port, $query);
    header("location: /status/success");
}

function addServer(string $name, string $caption, string $desc, string $address, $port, $query) {
    include($_SERVER['DOCUMENT_ROOT'] . "/src/db/Database.php");

    $prep = $connection->prepare(
        "INSERT INTO serverlist (title, address, port, caption, description) 
        VALUES (:title, :address, :port, :caption, :description)"
    );

    $prep->bindParam(":title", $name, PDO::PARAM_STR);
    $prep->bindParam(":address", $address, PDO::PARAM_STR);
    $prep->bindParam(":port", $port, PDO::PARAM_INT);
    $prep->bindParam(":caption", $caption, PDO::PARAM_STR);
    $prep->bindParam(":description", $desc, PDO::PARAM_STR);
    $prep->execute();

    $list = $connection->query("SELECT * FROM serverlist");
    $list->setFetchMode(PDO::FETCH_ASSOC);

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        if ($row["address"] == $address and $row["port"] == $port) {
            $connection->prepare("INSERT INTO querydata (id, status, players, maxplayers, version, hostname) 
                VALUES (:id, :status, :players, :maxplayers, :version, :hostname)
                ON DUPLICATE KEY UPDATE id=VALUES(id), status=VALUES(status), players=VALUES(players), version=VALUES(version), hostname=VALUES(hostname)"
            )->execute([
                "id" => $row["id"],
                "status" => "online",
                "players" => $query["Players"],
                "maxplayers" => $query["MaxPlayers"],
                "version" => $query["Version"],
                "hostname" => $query["HostName"]
            ]);
            break;
        }
    }
}
