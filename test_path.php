<?php
require_once __DIR__ . '/php/config.php';

echo "<h2>Path Debugging</h2>";
echo "<pre>";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'not set') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n";
echo "PROJECT_ROOT: " . realpath(__DIR__) . "\n";
echo "\n";
echo "APP_BASE_PATH: [" . APP_BASE_PATH . "]\n";
echo "\n";
echo "app_url('php/auth.php'): " . app_url('php/auth.php') . "\n";
echo "app_url('auth/login.php'): " . app_url('auth/login.php') . "\n";
echo "</pre>";
