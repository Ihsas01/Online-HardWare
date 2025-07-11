<?php
require_once 'config.php';

try {
    $conn = getDBConnection();
    
    // Read the SQL file
    $sql = file_get_contents('../data/insert_categories.sql');
    
    // Split the SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
        }
    }
    
    echo "Successfully populated database with categories and products.\n";
    echo "Added 15 categories and 30 sample products.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 