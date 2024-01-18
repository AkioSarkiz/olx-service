<?php

require __DIR__ . './../helpers.php';

$supportedMethods = ['POST'];

if (!in_array($_SERVER['REQUEST_METHOD'], $supportedMethods)) {
    sendResponse(["status" => 405, "message" => "Method Not Allowed"]);
}

// TODO: subscription logic
