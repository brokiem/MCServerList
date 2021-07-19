<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Server List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css">
    <meta content="https://www-mcserverlist.herokuapp.com/" property="og:url"/>
    <meta content="MC Server List" property="og:title"/>
    <meta content="https://www-mcserverlist.herokuapp.com/assets/icon/icon.png" property="og:image"/>
    <meta content="Minecraft: Bedrock Edition Server List" property="og:description"/>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-F0YD0SXLV4"></script>
    <script src="/assets/js/sweetalert2.min.js"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'G-F0YD0SXLV4');

        function onSubmit(token) {
            document.getElementById("addserver-form").submit();
        }
    </script>
</head>

<body>
<ul class="circles">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>

<nav class="navbar navbar-expand-lg navbar-dark navbar-bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand zoom-in" href="/"><img src="/assets/icon/icon.png" alt="" width="30" height="30"
                                                      class="d-inline-block align-text-top"> MC Server List</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto flex-nowrap">
                <a class="nav-link effect-underline" aria-current="page" href="/server/list">Home</a>
                <a class="nav-link active effect-underline" href="/server/add">Add Server</a>
                <a class="nav-link effect-underline" href="/server/edit">Edit Server</a>
            </div>
        </div>
    </div>
</nav>

<br>
<br>

<?php
function validate(mixed $captcha, string $name, string $caption, string $desc, string $banner, string $address, $port) {
    include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php');

    $name = htmlspecialchars($name, ENT_COMPAT, "ISO-8859-1");
    $caption = htmlspecialchars($caption, ENT_COMPAT, "ISO-8859-1");
    $desc = htmlspecialchars($desc, ENT_COMPAT, "ISO-8859-1");
    $banner = htmlspecialchars($banner, ENT_COMPAT, "ISO-8859-1");
    $address = htmlspecialchars($address, ENT_COMPAT, "ISO-8859-1");
    $port = htmlspecialchars($port, ENT_COMPAT, "ISO-8859-1");

    if ($captcha == null) {
        echo "<script type='text/javascript'> Swal.fire('Failed!', 'Data invalid. Please try again', 'error').then(function() {window.history.go(-1);}) </script>";
        die();
    }

    $recaptcha = new ReCaptcha\ReCaptcha($captcha_secret_key);
    $response = $recaptcha->verify($captcha, $_SERVER["REMOTE_ADDR"]);

    if (!$response->isSuccess()) {
        echo "<script type='text/javascript'> Swal.fire('Failed!', 'Data invalid. Please try again', 'error').then(function() {window.history.go(-1);}) </script>";
        die();
    }

    if (strlen($name) < 5 or strlen($caption) < 5 or strlen($name) >= 32 or strlen($caption) >= 128 or strlen($desc) >= 2048 or strlen($banner) >= 1024 or strlen($address) >= 64 or strlen($port) >= 8) {
        echo "<script type='text/javascript'> Swal.fire('Failed!', 'Data invalid. Please try again', 'error').then(function() {window.history.go(-1);}) </script>";
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
            echo "<script type='text/javascript'> Swal.fire('Failed!', 'Server with that address and port is exists', 'error').then(function() {window.history.go(-1);}) </script>";
            die();
        }
    }

    include($_SERVER["DOCUMENT_ROOT"] . "/src/query/Query.php");
    $query = query($address, (int)$port);

    if (!$query) {
        echo "<script type='text/javascript'> Swal.fire('Failed!', 'Server offline or query disabled', 'error').then(function() {window.history.go(-1);}) </script>";
        die();
    }

    $key = addServer($name, $caption, $desc, $banner, $address, (int)$port, $query);
    echo "<script type='text/javascript'> Swal.fire({title: 'Success!', icon: 'success', html: 'Save this server key to edit the server<br><b>$key</b>'}).then(function() {window.location.href = '/server/list'}) </script>";
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

if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["g-recaptcha-response"])) {
    $captchaRes = $_POST["g-recaptcha-response"] ?? null;
    $serverName = $_POST["serverName"];
    $serverCaption = $_POST["serverCaption"];
    $serverDesc = $_POST["serverDescription"] ?? "";
    $serverBannerURL = $_POST["serverBanner"] ?? "";
    $address = $_POST["serverAddress"];
    $port = $_POST["serverPort"];

    validate($captchaRes, $serverName, $serverCaption, $serverDesc, $serverBannerURL, $address, $port);
    die();
}
?>

<div class="container">
    <div class="row">
        <form id="addserver-form" method="post" action="/server/add.php">
            <div class="mb-4">
                <label for="serverName" class="form-label">Server name *</label>
                <input type="text" class="form-control border-0 shadow-sm px-4" name="serverName" minlength="3"
                       maxlength="32" required>
            </div>
            <div class="mb-4">
                <label for="serverCaption" class="form-label">Server caption *</label>
                <input type="text" class="form-control border-0 shadow-sm px-4" name="serverCaption" minlength="10"
                       maxlength="128" required>
            </div>
            <div class="mb-3">
                <label for="serverDescription" class="form-label">Server description (Optional)</label>
                <textarea class="form-control border-0 shadow-sm px-4" name="serverDescription" rows="3"
                          maxlength="2048"></textarea>
            </div>
            <div class="mb-4">
                <label for="serverBanner" class="form-label">Raw PNG URL server banner (Optional)</label>
                <textarea type="text" class="form-control border-0 shadow-sm px-4" name="serverBanner"
                          maxlength="1024"></textarea>

            </div>
            <br>
            <label for="serverInfo" class="form-label">Server address and port *</label>
            <div class="input-group mb-4">
                <input type="text" class="form-control border-0 shadow-sm px-4" placeholder="Address"
                       name="serverAddress" minlength="3"
                       maxlength="64" required>
                <span class="input-group-text">::</span>
                <input type="number" class="form-control border-0 shadow-sm px-4" placeholder="Port" name="serverPort"
                       value="19132" maxlength="8"
                       min="1" max="65535" minlength="1" data-bind="value:replyNumber" required>
            </div>
            <br>
            <div class="d-grid gap-2">
                <button id="sumbitBtn" type="submit"
                        class="g-recaptcha btn btn-primary btn-block text-uppercase mb-2 shadow-sm"
                        data-sitekey="6LfMuaAbAAAAADScAtQz8R0GyH_YmtCHBQZvErLT" data-callback='onSubmit'>Submit
                </button>
            </div>
        </form>
    </div>
    <br>
    <br>
    <br>
</div>
</body>