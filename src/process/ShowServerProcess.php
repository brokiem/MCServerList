<?php

include($_SERVER["DOCUMENT_ROOT"] . "/src/db/Database.php");

$offset = $_GET["offset"] ?? 0;
$total = $_GET["total"] ?? 0;

if ($total === 0) {
    die();
}

$list = $connection->prepare("SELECT * FROM serverlist LIMIT :offset, :total");

$list->bindValue(":offset", $offset, PDO::PARAM_INT);
$list->bindValue(":total", $total, PDO::PARAM_INT);
$list->execute();

$list->setFetchMode(PDO::FETCH_ASSOC);

$data = [];

while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
    $query = $connection->query("SELECT * FROM querydata");
    $query->setFetchMode(PDO::FETCH_ASSOC);

    while (($rowQ = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        if (($row["id"] === $rowQ["id"]) && isset($rowQ["players"])) {
            $data[$row["id"]] = [
                "status" => $rowQ["status"],
                "title" => $row["title"],
                "address" => $row["address"],
                "port" => $row["port"],
                "version" => $rowQ["version"],
                "players" => $rowQ["players"],
                "maxplayers" => $rowQ["maxplayers"],
                "hostname" => $rowQ["hostname"],
                "caption" => $row["caption"],
                "banner" => $row["banner"]
            ];
        }
    }
}

if (empty($data)) {
    echo '<div class="servers shadow p-3 mb-5 card mb-3 card-bg-dark"><div class="card-body card-bg-dark rounded">
		<h3 class="card-title">No more servers found :(</h3>
		<p class="card-text">Register more server now. <a href="/server/add">Click here</a></p>
		</div>';
    die();
}

shuffle($data); // shuffle servers

foreach ($data as $id => $row) {
    $banner = $row["banner"] ?? "/assets/img/banner.min.png";
    $status = $row["status"] === "offline" ? '<span class="badge badge-danger">Offline</span>' : '<span class="badge badge-success">Online</span>';
    echo '<div class="servers"><svg class="bd-placeholder-img card-img-top" width="100%" height="40" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" role="img"><img src=' . $banner . ' draggable="false" onmousedown="die() false" style="user-drag: none" class="img-fluid rounded" "></svg>';
    echo '<div class="servers shadow p-3 mb-5 card mb-3 card-bg-dark"><div class="card-body card-bg-dark rounded">
		<h3 class="card-title">' . $row["title"] . '</h3>
		<h5><span class="badge bg-secondary">' . $row["address"] . ':' . $row["port"] . '</span> ' . $status . ' <span class="badge bg-primary">' . $row["version"] . '</span> <span class="badge bg-info">' . $row["players"] . '/' . $row["maxplayers"] . '</span></h5>
		<p class="card-text mcfont">' . parse_minecraft_colors($row["hostname"]) . '</p></div></div>';
}

function parse_minecraft_colors($string): string {
    $string = utf8_decode(htmlspecialchars($string, ENT_QUOTES));
    $string = preg_replace('/\xA7([0-9a-f])/i', '<span class="mc-color mc-$1">', $string, -1, $count) . str_repeat("</span>", $count);
    return utf8_encode(preg_replace('/\xA7([k-or])/i', '<span class="mc-$1">', $string, -1, $count) . str_repeat("</span>", $count));
}

$data = [];