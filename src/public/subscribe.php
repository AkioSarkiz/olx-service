<?php

require __DIR__ . './../helpers.php';
require __DIR__ . './../db.php';

function validateData(array $data) {
    $isValid = array_key_exists('email', $data)
        && $data['email']
        && trim($data['email'])
        && filter_var($data['email'], FILTER_VALIDATE_EMAIL)
        && array_key_exists('ad', $data)
        && $data['ad']
        && trim($data['ad'])
        && filter_var($data['ad'], FILTER_VALIDATE_URL)
        && in_array(parse_url($data['ad'], PHP_URL_HOST), [
            'm.olx.ua', 'olx.ua', 'www.olx.ua', 
            'm.olx.bg', 'olx.bg', 'www.olx.bg',
            'm.olx.pl', 'olx.pl', 'www.olx.pl',
            'm.olx.ro', 'olx.ro', 'www.olx.ro',
            'm.olx.pt', 'olx.pt', 'www.olx.pt',
        ])
        && str_ends_with(parse_url($data['ad'], PHP_URL_PATH), '.html');

    if (!$isValid) {
        sendResponse(["status" => 422, "message" => "Data is invalid"]);
    }
}

$supportedMethods = ['POST'];

if (!in_array($_SERVER['REQUEST_METHOD'], $supportedMethods)) {
    sendResponse(["status" => 405, "message" => "Method Not Allowed"]);
}

$body = json_decode(file_get_contents('php://input'), true);

if ($body == null) {
    sendResponse(["status" => 400, "message" => "Bad request"]);
}

validateData($body);

$mysqli->begin_transaction();

try {
    $userId = null;
    $adId = null;

    // insert user
    $stmt = $mysqli->prepare("INSERT IGNORE INTO `users` (`email`) VALUES (?)");
    $stmt->bind_param('s', $body['email']);
    $stmt->execute();

    // load user id
    if (!$stmt->insert_id) {
        $stmt = $mysqli->prepare('SELECT `id` FROM `users` WHERE `email` = ?');
        $stmt->bind_param('s', $body['email']);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $records = $result->fetch_all(MYSQLI_ASSOC);
        
        if (count($records)) {
            $userId = $records[0]['id'];
        } else {
            throw new Error();
        }
    } else {
        $userId = $stmt->insert_id;
    }

    // insert ad
    $stmt = $mysqli->prepare("INSERT IGNORE INTO `ads` (`url`) VALUES (?)");
    $stmt->bind_param('s', $body['ad']);
    $stmt->execute();

    // load ad id
    if (!$stmt->insert_id) {
        $stmt = $mysqli->prepare('SELECT `id` FROM `ads` WHERE `url` = ?');
        $stmt->bind_param('s', $body['ad']);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $records = $result->fetch_all(MYSQLI_ASSOC);

        if (count($records)) {
            $adId = $records[0]['id'];
        } else {
            throw new Error();   
        }
    } else {
        $adId = $stmt->insert_id;
    }

    // insert ad_on_user
    $stmt = $mysqli->prepare("INSERT IGNORE INTO `ad_on_user` (`user_id`, `ad_id`) VALUES (?, ?)");
    $stmt->bind_param('ii', $userId, $adId);
    $stmt->execute();

    $mysqli->commit();

    sendResponse(['status' => 200, 'message' => 'success']);
} catch (\Error $e) {
    sendResponse(['status' => 500, 'message' => 'Server error']);
    $mysqli->rollback();
} finally {
    $stmt->close();
    $mysqli->close();
}

