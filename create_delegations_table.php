<?php
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/config/constants.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS delegations (
        delegation_id INT PRIMARY KEY AUTO_INCREMENT,
        delegator_user_id INT NOT NULL,
        delegate_user_id INT NOT NULL,
        start_date DATETIME NOT NULL,
        end_date DATETIME NOT NULL,
        status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (delegator_user_id) REFERENCES users(user_id),
        FOREIGN KEY (delegate_user_id) REFERENCES users(user_id)
    ) ENGINE=InnoDB";
    
    Database::query($sql);
    echo "Delegations table created successfully." . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
