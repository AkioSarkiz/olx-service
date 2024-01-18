<?php

function sendResponse(array $data): void {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
