<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$dir = __DIR__ . '/../data/loops';
$out = [];

if (is_dir($dir)) {
    foreach (glob($dir . '/*.json') as $f) {
        $j = json_decode(file_get_contents($f), true);
        if (is_array($j)) {
            $out[] = $j;
        }
    }
}

echo json_encode($out, JSON_UNESCAPED_UNICODE);
