<?php
/**
 * Base path helpers to make the app portable when it is served
 * from a subdirectory (e.g. http://example.com/gestion_reclamations/).
 */

if (!defined('APP_BASE_PATH')) {
    // Extract base path from REQUEST_URI
    // Example: /gestion-reclamations/auth/login.php -> /gestion-reclamations
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

    // Convert to forward slashes
    $scriptName = str_replace('\\', '/', $scriptName);

    // Extract the base directory from the script name
    // /gestion-reclamations/auth/login.php -> /gestion-reclamations
    // /gestion-reclamations/php/auth.php -> /gestion-reclamations
    $parts = explode('/', trim($scriptName, '/'));

    $basePath = '';
    if (count($parts) > 1) {
        // First part is the project folder (gestion-reclamations)
        $basePath = '/' . $parts[0];
    }

    // If we're at root level (no subdirectory), basePath is empty
    if ($basePath === '/' || $basePath === '' || in_array($parts[0], ['index.php', 'test_path.php'])) {
        $basePath = '';
    }

    define('APP_BASE_PATH', $basePath);
}

/**
 * Prefix a relative path with the detected base path.
 * Path should be relative from project root (e.g., 'auth/login.php', 'php/auth.php')
 */
function app_url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    return APP_BASE_PATH . $path;
}

/**
 * Redirect helper that is aware of the base path.
 * Path should be relative from project root (e.g., 'auth/login.php')
 */
function redirect_to(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}
