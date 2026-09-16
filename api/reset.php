<?php
header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
$code = $data['code'] ?? '';

// Change this code before going live if you want a different one.
$RESET_CODE = 'RESET-CAFE-2026';

if (!hash_equals($RESET_CODE, (string)$code)) {
    http_response_code(403);
    echo json_encode(['error' => 'invalid_code']);
    exit;
}

$loopsDir = __DIR__ . '/../data/loops';
$photosDir = __DIR__ . '/../data/photos';
$deleted = 0;

foreach (glob($loopsDir . '/*.json') as $f) {
    if (unlink($f)) $deleted++;
}
foreach (glob($photosDir . '/*') as $f) {
    $base = basename($f);
    if ($base === '.htaccess' || $base === '.gitkeep') continue;
    if (is_file($f) && unlink($f)) $deleted++;
}

echo json_encode(['ok' => true, 'deleted' => $deleted]);
