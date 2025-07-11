<?php
require_once 'config.php';

try {
    $conn = getDBConnection();
    
    // Read and execute the SQL file
    $sql = file_get_contents('../data/insert_data.sql');
    
    // Split the SQL file into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
        }
    }
    
    echo "Database populated successfully!\n";
    echo "Added 15 categories and 15 sample products.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 