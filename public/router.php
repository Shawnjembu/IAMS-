<?php
$url = $_SERVER['REQUEST_URI'];
$url = parse_url($url, PHP_URL_PATH);
if ($url !== '/' && file_exists(__DIR__ . $url)) {
    return false;
}
$_GET['url'] = ltrim($url, '/');
require __DIR__ . '/index.php';