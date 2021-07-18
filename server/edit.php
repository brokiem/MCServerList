<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MC Servers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand:300,400">
    <link rel="icon" href="/assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css">
    <meta content="https://www-mcserverlist.herokuapp.com/" property="og:url"/>
    <meta content="MC Server List" property="og:title"/>
    <meta content="https://www-mcserverlist.herokuapp.com/assets/icon/icon.png" property="og:image"/>
    <meta content="Minecraft: Bedrock Edition Servers List" property="og:description"/>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            document.getElementById("editserver-form").submit();
        }
    </script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand zoom-in px-5" href="/"><b><img src="/assets/icon/icon.png" alt="" width="30" height="30"
                                                              class="d-inline-block align-text-top"> MC Servers Beta</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto flex-nowrap px-5">
                <a class="nav-link effect-underline" aria-current="page" href="/server/list">Home</a>
                <a class="nav-link effect-underline" href="/server/add">Add Server</a>
                <a class="nav-link active effect-underline" href="/server/edit">Edit Server</a>
            </div>
        </div>
    </div>
</nav>

<br>
<br>

<div class="container">
    <?php
    function validate(int|string $key, string $name, string $caption, string $desc, string $banner, string $address, $port) {
        include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

        $name = htmlspecialchars($name, ENT_COMPAT, "ISO-8859-1");
        $caption = htmlspecialchars($caption, ENT_COMPAT, "ISO-8859-1");
        $desc = htmlspecialchars($desc, ENT_COMPAT, "ISO-8859-1");
        $banner = htmlspecialchars($banner, ENT_COMPAT, "ISO-8859-1");
        $address = htmlspecialchars($address, ENT_COMPAT, "ISO-8859-1");
        $port = htmlspecialchars($port, ENT_COMPAT, "ISO-8859-1");

        if (strlen($name) >= 32 or strlen($caption) >= 128 or strlen($desc) >= 2048 or strlen($banner) >= 1024 or strlen($address) >= 64 or strlen($port) >= 8) {
            echo "<script type='text/javascript'> Swal.fire('Error!', 'Data invalid. Please try again', 'error').then(function() {window.history.go(-1);}) </script>";
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
                echo "<script type='text/javascript'> Swal.fire('Error!', 'Server with that address and port is exists', 'error').then(function() {window.history.go(-1);}) </script>";
                die();
            }
        }

        include($_SERVER["DOCUMENT_ROOT"] . "/src/query/Query.php");
        $query = query($address, (int)$port);

        if (!$query) {
            echo "<script type='text/javascript'> Swal.fire('Error!', 'Server offline or query disabled', 'error').then(function() {window.history.go(-1);}) </script>";
            die();
        }

        editServer($key, $name, $caption, $desc, $banner, $address, (int)$port, $query);
        echo "<script type='text/javascript'> Swal.fire('Success!', '', 'success').then(function() {window.location.href = '/server/list'}) </script>";
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

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["serverKey"])) {
            $key = $_POST["serverKey"];
            $serverName = $_POST["serverName"];
            $serverCaption = $_POST["serverCaption"];
            $serverDesc = $_POST["serverDescription"] ?? "";
            $serverBannerURL = $_POST["serverBanner"] ?? "";
            $address = $_POST["serverAddress"];
            $port = $_POST["serverPort"];

            validate($key, $serverName, $serverCaption, $serverDesc, $serverBannerURL, $address, $port);
            die();
        }

        include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");
        require_once($_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php');

        $captchaRes = $_POST["g-recaptcha-response"] ?? null;
        $adminKey = $_POST["adminKey"];

        if ($captchaRes == null || strlen((string)$adminKey) < 10) {
            header("location: /server/edit");
            die();
        }

        $recaptcha = new ReCaptcha\ReCaptcha($captcha_secret_key);
        $response = $recaptcha->verify($captchaRes, $_SERVER["REMOTE_ADDR"]);

        if (!$response->isSuccess()) {
            header("location: /server/edit");
            die();
        }

        if (!isset($adminKey)) {
            header("location: /");
            die();
        }

        $query = $connection->query("SELECT * FROM serverlist");
        $query->setFetchMode(PDO::FETCH_ASSOC);

        $found = false;
        while (($row = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            if ($row["adminkey"] === $adminKey) {
                $found = true;
                $title = $row["title"];
                $address = $row["address"];
                $port = $row["port"];
                $caption = $row["caption"];
                $description = $row["description"];
                $banner = $row["banner"];

                echo "<div class='row'>
            <form id='addserver-form' method='post' action='/server/edit.php'>
                <div class='mb-4'>
                    <label for='serverKey' class='form-label'>Server Key (Secret!)</label>
                    <input type='text' class='form-control border-0 shadow-sm px-4' name='serverKey' minlength='3' maxlength='100' value='$adminKey' readonly>
                </div>
                <div class='mb-4'>
                    <label for='serverName' class='form-label'>Server name</label>
                    <input type='text' class='form-control border-0 shadow-sm px-4' name='serverName' minlength='3' maxlength='32' value='$title' required>
                </div>
                <div class='mb-4'>
                    <label for='serverCaption' class='form-label'>Server caption</label>
                    <input type='text' class='form-control border-0 shadow-sm px-4' name='serverCaption' minlength='10' maxlength='128' value='$caption' required>
                </div>
                <div class='mb-3'>
                    <label for='serverDescription' class='form-label'>Server description</label>
                    <textarea class='form-control border-0 shadow-sm px-4' name='serverDescription' rows='3' maxlength='2048' value='$description'></textarea>
                </div>
                <div class='mb-4'>
                    <label for='serverBanner' class='form-label'>Raw PNG URL server banner</label>
                    <textarea type='text' class='form-control border-0 shadow-sm px-4' name='serverBanner' maxlength='1024' value='$banner'></textarea>
                </div>
                <br>
                <label for='serverInfo' class='form-label'>Server address and port</label>
                <div class='input-group mb-4'>
                    <input type='text' class='form-control border-0 shadow-sm px-4' placeholder='Address' name='serverAddress' minlength='3'
                           maxlength='64' value='$address' required>
                    <span class='input-group-text'>::</span>
                    <input type='number' class='form-control border-0 shadow-sm px-4' placeholder='Port' name='serverPort' value='19132' maxlength='8'
                           min='1' max='65535' minlength='1' data-bind='value:replyNumber' value='$port' required>
                </div>
                <br>
                <div class='d-grid gap-2'>
                    <button id='sumbitBtn' type='submit' class='btn btn-primary btn-block text-uppercase mb-2 shadow-sm'>Submit</button>
                </div>
            </form>
        </div><br><br><br>";
                break;
            }
        }

        if (!$found) {
            echo '<div class="row"><div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4">Server not found.</h3>
            <p class="text-muted mb-4">Server not found with that key</p>
            <div class="d-grid gap-2"><button class="btn btn-primary btn-block text-uppercase mb-2 shadow-sm" onclick="window.history.go(-1); return false;">Back</button></div></div></div>';
        }
        die();
    }
    ?>

    <div class="row">
        <div class="col-lg-10 col-xl-7 mx-auto">
            <h3 class="display-4">Server key</h3>
            <p class="text-muted mb-4">Enter the server key that was given when registering the server</p>

            <form id="editserver-form" method="post" action="/server/edit.php">
                <div class="form-group mb-3">
                    <input name="adminKey" type="text" placeholder="Server key" required=""
                           class="form-control border-0 shadow-sm px-4">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="g-recaptcha btn btn-primary btn-block text-uppercase mb-2 shadow-sm"
                            data-sitekey="6LfMuaAbAAAAADScAtQz8R0GyH_YmtCHBQZvErLT" data-callback='onSubmit'>Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>