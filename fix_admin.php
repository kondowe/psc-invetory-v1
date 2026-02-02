<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/core/Database.php';

try {
    $password = 'Admin@123';
    // Generate a fresh hash using your system's current default settings
    $hash = password_hash($password, PASSWORD_BCRYPT);
    
    echo "Updating admin password...<br>";
    echo "New Hash: $hash <br>";
    
    $updated = Database::query("UPDATE users SET password_hash = ? WHERE username = 'admin'", [$hash]);
    
    if ($updated) {
        echo "<h2 style='color:green'>SUCCESS: Admin password reset to 'Admin@123'</h2>";
        echo "<p>Please try logging in now at <a href='index.php'>the login page</a>.</p>";
    } else {
        echo "<h2 style='color:red'>FAILURE: Could not update database.</h2>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
