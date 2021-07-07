<?php

$page = $_GET;

if (empty($page)) {
    header("location: /");
    return;
}

if (isset($page["captcha"])) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>MC Servers</title><meta content="width=device-width, initial-scale=1" name="viewport"><link href="https://fonts.googleapis.com/css?family=Quicksand:300,400" rel="stylesheet"><link href="/assets/icon/favicon.ico" rel="icon" type="image/x-icon"><link href="/assets/css/animate.css" rel="stylesheet"><link href="/assets/css/styles.css" rel="stylesheet"><link href="/assets/css/bootstrap.min.css" rel="stylesheet"> <script src="/assets/js/jquery.min.js"></script> <script src="/assets/js/bootstrap.bundle.min.js"></script> </head><body><div id="space"></div><div class="container"><div class="shadow p-3 mb-5 card mb-3 card-bg-dark"><div class="card-body card-bg-dark rounded"><div class="text-center"><h1 class="display-3">Captcha!</h1><p class="lead">Captcha was not validated.</p><br><br><p class="lead"> <a class="btn btn-primary" onclick="window.history.go(-1); return false;" role="button">Back to previus page</a></p></div></div></div></div></body></html>';
} elseif (isset($page["failed"])) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>MC Servers</title><meta content="width=device-width, initial-scale=1" name="viewport"><link href="https://fonts.googleapis.com/css?family=Quicksand:300,400" rel="stylesheet"><link href="/assets/icon/favicon.ico" rel="icon" type="image/x-icon"><link href="/assets/css/animate.css" rel="stylesheet"><link href="/assets/css/styles.css" rel="stylesheet"><link href="/assets/css/bootstrap.min.css" rel="stylesheet"> <script src="/assets/js/jquery.min.js"></script> <script src="/assets/js/bootstrap.bundle.min.js"></script> </head><body><div id="space"></div><div class="container"><div class="shadow p-3 mb-5 card mb-3 card-bg-dark"><div class="card-body card-bg-dark rounded"><div class="text-center"><h1 class="display-3">Failed!</h1><p class="lead">The server you registered is offline, the query is not turned on or server is already registered.</p><br><br><p class="lead"> <a class="btn btn-primary" href="/" role="button">Continue to homepage</a></p></div></div></div></div></body></html>';
} elseif (isset($page["success"])) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>MC Servers</title><meta content="width=device-width, initial-scale=1" name="viewport"><link href="https://fonts.googleapis.com/css?family=Quicksand:300,400" rel="stylesheet"><link href="/assets/icon/favicon.ico" rel="icon" type="image/x-icon"><link href="/assets/css/animate.css" rel="stylesheet"><link href="/assets/css/styles.css" rel="stylesheet"><link href="/assets/css/bootstrap.min.css" rel="stylesheet"> <script src="/assets/js/jquery.min.js"></script> <script src="/assets/js/bootstrap.bundle.min.js"></script> </head><body><div id="space"></div><div class="container"><div class="shadow p-3 mb-5 card mb-3 card-bg-dark"><div class="card-body card-bg-dark rounded"><div class="text-center"><h1 class="display-3">Success!</h1><p class="lead">Your server has been registered.<br>Your server will be displayed in about 5 minutes.</p><br><br><p class="lead"> <a class="btn btn-primary" href="/" role="button">Continue to homepage</a></p></div></div></div></div></body></html>';
} else {
    header("location: /");
}

