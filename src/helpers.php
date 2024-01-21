<?php

if (!function_exists('sendResponse')) {
    function sendResponse(array $data): void {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($data['status']);
        echo json_encode($data);
    }    
}
