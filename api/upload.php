<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (empty($_FILES['photo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'no_file']);
    exit;
}

$f = $_FILES['photo'];

if ($f['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'upload_error']);
    exit;
}

// 6MB ceiling per photo
if ($f['size'] > 6 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode(['error' => 'too_large']);
    exit;
}

$info = @getimagesize($f['tmp_name']);
if (!$info) {
    http_response_code(400);
    echo json_encode(['error' => 'not_an_image']);
    exit;
}

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$mime = $info['mime'];
if (!isset($allowed[$mime])) {
    http_response_code(400);
    echo json_encode(['error' => 'unsupported_type']);
    exit;
}

$dir = __DIR__ . '/../data/photos';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$name = bin2hex(random_bytes(14)) . '.' . $allowed[$mime];
$dest = $dir . '/' . $name;

if (!move_uploaded_file($f['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'move_failed']);
    exit;
}

echo json_encode(['ok' => true, 'url' => 'data/photos/' . $name]);
