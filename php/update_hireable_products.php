<?php
require_once 'config.php';

try {
    $conn = getDBConnection();
    if ($conn === null) {
        die("Database connection failed");
    }

    // Update some products to be available for hire
    $updateQuery = "UPDATE products 
                   SET available_for_hire = 1,
                       daily_rate = 25.00,
                       weekly_rate = 150.00,
                       monthly_rate = 500.00
                   WHERE id IN (1, 2, 3, 4, 5)";
    
    $conn->exec($updateQuery);
    echo "Successfully updated products to be available for hire";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> 