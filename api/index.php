<?php
/**
 * Vercel Front Controller / Serverless Entrypoint
 * Consolidates all PHP requests into a single serverless function
 * to comply with Vercel Hobby plan limits (max 12 functions).
 */

// Define root directory and change working directory so relative includes in all scripts work as expected
$rootDir = dirname(__DIR__);
chdir($rootDir);

$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$uriPath = parse_url($rawUri, PHP_URL_PATH) ?? '/';
$path = ltrim($uriPath, '/');

// 1. Root route -> index.php
if ($path === '' || $path === '/') {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
    $_SERVER['SCRIPT_FILENAME'] = $rootDir . DIRECTORY_SEPARATOR . 'index.php';
    require $rootDir . DIRECTORY_SEPARATOR . 'index.php';
    exit;
}

// 2. Direct match for a PHP file (e.g., blog.php, api/get_articles.php)
$targetFile = realpath($rootDir . DIRECTORY_SEPARATOR . $path);
if ($targetFile && strpos($targetFile, $rootDir) === 0 && is_file($targetFile)) {
    if (pathinfo($targetFile, PATHINFO_EXTENSION) === 'php') {
        $_SERVER['SCRIPT_NAME'] = '/' . $path;
        $_SERVER['PHP_SELF'] = '/' . $path;
        $_SERVER['SCRIPT_FILENAME'] = $targetFile;
        require $targetFile;
        exit;
    }
}

// 3. Match without .php extension (e.g., /blog -> blog.php, /api/get_articles -> api/get_articles.php)
$targetWithPhp = realpath($rootDir . DIRECTORY_SEPARATOR . $path . '.php');
if ($targetWithPhp && strpos($targetWithPhp, $rootDir) === 0 && is_file($targetWithPhp)) {
    $_SERVER['SCRIPT_NAME'] = '/' . $path . '.php';
    $_SERVER['PHP_SELF'] = '/' . $path . '.php';
    $_SERVER['SCRIPT_FILENAME'] = $targetWithPhp;
    require $targetWithPhp;
    exit;
}

// 4. Fallback 404
http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo "404 Not Found";
