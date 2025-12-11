<?php
/**
 * Base path helpers to make the app portable when it is served
 * from a subdirectory (e.g. http://example.com/gestion_reclamations/).
 */

if (!defined('APP_BASE_PATH')) {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $dirName = str_replace('\\', '/', dirname($scriptName));
    $basePath = rtrim($dirName, '/');

    if ($basePath === '/') {
        $basePath = '';
    }

    define('APP_BASE_PATH', $basePath);
}

/**
 * Prefix a relative path with the detected base path.
 */
function app_url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    return APP_BASE_PATH . $path;
}

/**
 * Redirect helper that is aware of the base path.
 */
function redirect_to(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}
