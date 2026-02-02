<?php
/**
 * Login Diagnostic Tool
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Logger.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/core/Auth.php';

echo "<h1>Login Diagnostic Tool</h1>";

try {
    // 1. Check Database Connection
    echo "<h3>1. Database Connection</h3>";
    $db = Database::getInstance();
    if ($db) {
        echo "<p style='color:green'>SUCCESS: Connected to database.</p>";
    }

    // 2. Check Admin User
    echo "<h3>2. User Check (admin)</h3>";
    $user = Database::fetchOne("SELECT * FROM users WHERE username = 'admin'");
    if ($user) {
        echo "<p style='color:green'>SUCCESS: User 'admin' found.</p>";
        echo "<ul>
                <li>User ID: {$user['user_id']}</li>
                <li>Status: {$user['status']}</li>
                <li>Hash: <span style='font-family:monospace'>{$user['password_hash']}</span></li>
              </ul>";
    } else {
        echo "<p style='color:red'>FAILURE: User 'admin' not found!</p>";
    }

    // 3. Password Hashing Test
    echo "<h3>3. Password Hashing Logic</h3>";
    $testPassword = 'Admin@123';
    $isMatch = password_verify($testPassword, $user['password_hash']);
    
    if ($isMatch) {
        echo "<p style='color:green'>SUCCESS: password_verify() matches the stored hash for 'Admin@123'.</p>";
    } else {
        echo "<p style='color:red'>FAILURE: password_verify() does NOT match the stored hash.</p>";
        
        // Let's see what a fresh hash looks like
        $freshHash = password_hash($testPassword, PASSWORD_BCRYPT);
        echo "<p>Generated fresh hash for 'Admin@123': <span style='font-family:monospace'>$freshHash</span></p>";
        
        if (password_verify($testPassword, $freshHash)) {
            echo "<p style='color:green'>Note: Fresh hash works correctly. Stored hash is likely corrupted or mismatched.</p>";
        }
    }

    // 4. Auth Attempt Simulation
    echo "<h3>4. Simulating Auth::attempt('admin', 'Admin@123')</h3>";
    Session::start();
    $result = Auth::attempt('admin', 'Admin@123');
    
    if ($result['success']) {
        echo "<p style='color:green'>SUCCESS: Auth::attempt successful!</p>";
        echo "<p>Session User ID: " . Session::get(SESSION_USER_ID) . "</p>";
    } else {
        echo "<p style='color:red'>FAILURE: Auth::attempt failed. Message: {$result['message']}</p>";
    }

} catch (Exception $e) {
    echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr><p>Please delete this file after testing for security.</p>";
