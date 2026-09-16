<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$id = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['id'] ?? '');
$file = __DIR__ . '/../data/loops/' . $id . '.json';

if ($id === '' || !file_exists($file)) {
    http_response_code(404);
    echo json_encode(null);
    exit;
}

echo file_get_contents($file);
