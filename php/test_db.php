<?php
require_once 'config.php';

try {
    // Test database connection
    $conn = getDBConnection();
    if ($conn === null) {
        die("Database connection failed");
    }
    echo "Database connection successful!<br>";

    // Check if tables exist
    $tables = [
        'categories',
        'products',
        'users',
        'orders',
        'order_items',
        'rental_requests',
        'rental_history',
        'contact_messages',
        'reviews',
        'wishlist',
        'cart'
    ];

    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "Table '$table' exists<br>";
        } else {
            echo "Table '$table' does not exist<br>";
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> 