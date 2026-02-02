<?php
/**
 * Routing Debug Script
 *
 * This script helps diagnose routing issues
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Routing Debug Information</h1>";

echo "<h2>Server Variables</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Path Parsing Logic</h2>";
echo "<pre>";

$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);

echo "1. Original REQUEST_URI: {$requestUri}\n";
echo "2. SCRIPT_NAME: {$scriptName}\n";
echo "3. Script Directory: {$scriptDir}\n";

// Path extraction (same as index.php)
$path = str_replace($scriptDir, '', $requestUri);
echo "4. After str_replace: {$path}\n";

$path = parse_url($path, PHP_URL_PATH);
echo "5. After parse_url: {$path}\n";

$path = trim($path, '/');
echo "6. After trim: {$path}\n";

// Fallback logic
$dirName = trim($scriptDir, '/');
echo "7. Directory name (trimmed): {$dirName}\n";

if (!empty($dirName) && strpos($path, $dirName . '/') === 0) {
    $path = substr($path, strlen($dirName) + 1);
    echo "8. After fallback strip: {$path}\n";
} else if ($path === $dirName) {
    $path = '';
    echo "8. Path equals dirName, set to empty\n";
}

// Default route
if (empty($path)) {
    $path = 'dashboard';
    echo "9. Empty path, defaulting to: {$path}\n";
}

// Split path
$parts = explode('/', $path);
$controller = $parts[0] ?? 'dashboard';
$action = $parts[1] ?? 'index';
$params = array_slice($parts, 2);

echo "\n10. Final parsed values:\n";
echo "    Controller: {$controller}\n";
echo "    Action: {$action}\n";
echo "    Params: " . json_encode($params) . "\n";
echo "</pre>";

// Check .htaccess
echo "<h2>.htaccess Check</h2>";
echo "<pre>";
$htaccessPath = __DIR__ . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo "✓ .htaccess exists\n\n";
    echo htmlspecialchars(file_get_contents($htaccessPath));
} else {
    echo "✗ .htaccess NOT FOUND\n";
    echo "This is likely the issue. URL rewriting won't work without .htaccess\n";
}
echo "</pre>";

// Check if mod_rewrite is enabled
echo "<h2>Apache mod_rewrite Check</h2>";
echo "<pre>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "✓ mod_rewrite is enabled\n";
    } else {
        echo "✗ mod_rewrite is NOT enabled\n";
        echo "You need to enable mod_rewrite in Apache configuration\n";
    }
} else {
    echo "⚠ Cannot determine if mod_rewrite is enabled (not running under Apache or apache_get_modules not available)\n";
}
echo "</pre>";
