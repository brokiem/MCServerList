<?php

header("location: /");

include("src/cachemanager.php");
if (checkLastQuery()) {
    startQuery();
}