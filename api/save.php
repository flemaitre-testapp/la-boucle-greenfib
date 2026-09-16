<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data) || empty($data['pubId']) || !preg_match('/^[a-zA-Z0-9]{1,40}$/', $data['pubId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_payload']);
    exit;
}

// basic size guard (raw JSON, incl. base64 fallback fields if any) — 8MB ceiling
if (strlen($raw) > 8 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode(['error' => 'too_large']);
    exit;
}

$dir = __DIR__ . '/../data/loops';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$file = $dir . '/' . $data['pubId'] . '.json';
$ok = file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));

if ($ok === false) {
    http_response_code(500);
    echo json_encode(['error' => 'write_failed']);
    exit;
}

echo json_encode(['ok' => true]);
