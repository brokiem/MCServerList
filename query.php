<?php

include("src/cachemanager.php");
if (checkLastQuery()) {
    startQuery();
}

header("location: /");