<?php

require_once('config.php');

$app = new app();
$request = new request();
$cmd = $app->handle_request($request);
if ($cmd) {
    $cmd->execute($request);
};