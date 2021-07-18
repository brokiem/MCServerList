<?php

if (empty($_POST)) {
    header("location: /");
    die();
}

$captchaRes = $_POST["g-recaptcha-response"] ?? null;
$serverName = $_POST["serverName"];
$serverCaption = $_POST["serverCaption"];
$serverDesc = $_POST["serverDescription"] ?? "";
$serverBannerURL = $_POST["serverBanner"] ?? "";
$address = $_POST["serverAddress"];
$port = $_POST["serverPort"];

validate($captchaRes, $serverName, $serverCaption, $serverDesc, $serverBannerURL, $address, $port);

function validate($captcha, string $name, string $caption, string $desc, string $banner, string $address, $port) {
    include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php');

    $name = htmlspecialchars($name, ENT_COMPAT, "ISO-8859-1");
    $caption = htmlspecialchars($caption, ENT_COMPAT, "ISO-8859-1");
    $desc = htmlspecialchars($desc, ENT_COMPAT, "ISO-8859-1");
    $banner = htmlspecialchars($banner, ENT_COMPAT, "ISO-8859-1");
    $address = htmlspecialchars($address, ENT_COMPAT, "ISO-8859-1");
    $port = htmlspecialchars($port, ENT_COMPAT, "ISO-8859-1");

    if ($captcha == null) {
        header("location: /status/captcha");
        die();
    }

    $recaptcha = new ReCaptcha\ReCaptcha($captcha_secret_key);
    $response = $recaptcha->verify($captcha, $_SERVER["REMOTE_ADDR"]);

    if (!$response->isSuccess()) {
        header("location: /status/captcha");
        die();
    }

    if (strlen($name) < 5 or strlen($caption) < 5 or strlen($name) >= 32 or strlen($caption) >= 128 or strlen($desc) >= 2048 or strlen($banner) >= 1024 or strlen($address) >= 64 or strlen($port) >= 8) {
        header("location: /status/failed");
        die();
    }

    if ($banner != "" or $banner != null) {
        if (!preg_match('/https?:\/\/[^?]*\.png(?![\w.\-_])/', $banner)) {
            header("location: /status/banner");
            die();
        }

        [$width, $height] = getimagesize($banner);

        if ($width !== 1170 and $height !== 180 or fsize($banner) > 1024) {
            header("location: /status/banner");
            die();
        }
    }

    $list = $connection->prepare("SELECT * FROM serverlist WHERE address = ?");
    $list->execute([$address]);
    $list->setFetchMode(PDO::FETCH_ASSOC);

    while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
        if ((int)$row["port"] === (int)$port) {
            header("location: /status/failed");
            die();
        }
    }

    include($_SERVER["DOCUMENT_ROOT"] . "/src/query/Query.php");
    $query = query($address, (int)$port);

    if (!$query) {
        header("location: /status/failed");
        die();
    }

    $key = addServer($name, $caption, $desc, $banner, $address, (int)$port, $query);
    header("location: /status/success?key=$key");
}

function addServer(string $name, string $caption, string|null $desc, string|null $banner, string $address, $port, $query): string {
    include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

    $desc = $desc == "" ? null : $banner;
    $banner = $banner == "" ? null : $banner;

    try {
        $adminKey = random();
    } catch (Exception $e) {
        header("location: /status/failed"); // should never happen
        die();
    }

    $prep = $connection->prepare(
        "INSERT INTO serverlist(title, address, port, caption, description, banner, adminkey)
        VALUES(:title, :address, :port, :caption, :description, :banner, :adminkey)"
    );

    $prep->bindParam(":title", $name, PDO::PARAM_STR);
    $prep->bindParam(":address", $address, PDO::PARAM_STR);
    $prep->bindParam(":port", $port, PDO::PARAM_INT);
    $prep->bindParam(":caption", $caption, PDO::PARAM_STR);
    $prep->bindParam(":description", $desc, PDO::PARAM_STR | PDO::PARAM_NULL);
    $prep->bindParam(":banner", $banner, PDO::PARAM_STR | PDO::PARAM_NULL);
    $prep->bindParam(":adminkey", $adminKey, PDO::PARAM_STR);
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

    return $adminKey;
}

function fsize($path): int|string {
    $fp = fopen($path, 'rb');
    $inf = stream_get_meta_data($fp);
    fclose($fp);
    foreach ($inf["wrapper_data"] as $v) {
        if (false !== stripos($v, "content-length")) {
            $v = explode(":", $v);
            return trim($v[1]);
        }
    }
    fclose($fp);
    return 0;
}

/**
 * @throws Exception
 */
function random(int $length = 20): string {
    $bytes1 = random_bytes($length / 2);
    $bytes2 = random_bytes($length / 2);
    $rand = ["-", "_", "=", "%", "."];
    return bin2hex($bytes1) . $rand[array_rand($rand)] . bin2hex($bytes2);
}