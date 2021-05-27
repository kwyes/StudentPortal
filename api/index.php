<?php
header('Access-Control-Allow-Origin: http://www.example.com');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
// echo __DIR__;

require_once __DIR__.'/../settings.php';
require_once __DIR__.'/../lib/api.php';
global $settings;
new API($settings);
