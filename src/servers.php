<?php

include("db/database.php");

$last_offset = "null";

$offset = $_GET['offset'] ?? 0;
$total = $_GET['total'] ?? 0;

if ($offset === $last_offset) {
    return false;
}

$last_offset = $offset;

if ($total === 0) {
    return false;
}

$list = $connection->query("SELECT * FROM serverlist LIMIT $offset, $total");
$list->setFetchMode(PDO::FETCH_ASSOC);

while (($row = $list->fetch(PDO::FETCH_ASSOC)) !== false) {
    $query = $connection->query("SELECT * FROM querydata");
    $query->setFetchMode(PDO::FETCH_ASSOC);

    while (($rowQ = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        if (($row["id"] === $rowQ["id"]) && isset($rowQ["players"])) {
            $status = $rowQ["status"] === "offline" ? '<span class="badge badge-danger">Offline</span>' : '<span class="badge badge-success">Online</span>';
            echo '<div class="servers"> <svg class="bd-placeholder-img card-img-top" width="100%" height="40" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" role="img"><img src="https://cdn.discordapp.com/attachments/833621011097845830/861064226637086750/crop.png" draggable="false" onmousedown="return false" style="user-drag: none" class="img-fluid rounded""></svg>';
            echo '<div class="shadow p-3 mb-5 card mb-3 card-bg-dark"> 
            <div class="card-body card-bg-dark rounded">
                <h3 class="card-title">' . $row["title"] . '</h3>
                <h5><span class="badge bg-secondary">' . $row["address"] . ':' . $row["port"] . '</span> ' . $status . ' <span class="badge bg-primary">' . $rowQ["version"] . '</span> <span class="badge bg-info">' . $rowQ["players"] . '/' . $rowQ["maxplayers"] . '</span></h5>
                <p class="card-text">' . $row["caption"] . '</p>
            </div></div></div>';
        }
    }
}