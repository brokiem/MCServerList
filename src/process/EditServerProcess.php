<?php

$key = $_POST["serverKey"];
$serverName = $_POST["serverName"];
$serverCaption = $_POST["serverCaption"];
$serverDesc = $_POST["serverDescription"] ?? "";
$serverBannerURL = $_POST["serverBanner"] ?? "";
$address = $_POST["serverAddress"];
$port = $_POST["serverPort"];

validate($key, $serverName, $serverCaption, $serverDesc, $serverBannerURL, $address, $port);

function validate(int|string $key, string $name, string $caption, string $desc, string $banner, string $address, $port) {
    include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

    $name = htmlspecialchars($name, ENT_COMPAT, "ISO-8859-1");
    $caption = htmlspecialchars($caption, ENT_COMPAT, "ISO-8859-1");
    $desc = htmlspecialchars($desc, ENT_COMPAT, "ISO-8859-1");
    $banner = htmlspecialchars($banner, ENT_COMPAT, "ISO-8859-1");
    $address = htmlspecialchars($address, ENT_COMPAT, "ISO-8859-1");
    $port = htmlspecialchars($port, ENT_COMPAT, "ISO-8859-1");

    if (strlen($name) >= 32 or strlen($caption) >= 128 or strlen($desc) >= 2048 or strlen($banner) >= 1024 or strlen($address) >= 64 or strlen($port) >= 8) {
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

    editServer($key, $name, $caption, $desc, $banner, $address, (int)$port, $query);
    //header("location: /status/success");
}

function editServer(string $key, string $name, string $caption, string|null $desc, string|null $banner, string $address, $port, $query): void {
    include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

    $desc = $desc == "" ? null : $banner;
    $banner = $banner == "" ? null : $banner;

    $prep = $connection->prepare("UPDATE serverlist SET title=:title,address=:address,port=:port,caption=:caption,description=:description,banner=:banner WHERE adminkey = :adminkey");

    $prep->bindParam(":title", $name, PDO::PARAM_STR);
    $prep->bindParam(":address", $address, PDO::PARAM_STR);
    $prep->bindParam(":port", $port, PDO::PARAM_INT);
    $prep->bindParam(":caption", $caption, PDO::PARAM_STR);
    $prep->bindParam(":description", $desc, PDO::PARAM_STR | PDO::PARAM_NULL);
    $prep->bindParam(":banner", $banner, PDO::PARAM_STR | PDO::PARAM_NULL);
    $prep->bindParam(":adminkey", $key, PDO::PARAM_STR);
    $prep->execute();
}