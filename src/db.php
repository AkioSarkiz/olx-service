<?php

require __DIR__ . '/helpers.php';

$mysqli = new mysqli(getenv('DB_HOST'), getenv("DB_USER"), getenv("DB_PASSWORD"), getenv("DB_NAME"));

if ($mysqli->connect_errno) {
    sendResponse(["status" => 500, "message" => "Server error"]);
}
