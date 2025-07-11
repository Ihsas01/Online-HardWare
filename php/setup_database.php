<?php
require_once 'config.php';

try {
    // Create connection without database
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    $conn->exec($sql);
    echo "Database created successfully or already exists\n";
    
    // Select the database
    $conn->exec("USE " . DB_NAME);
    
    // Read and execute the schema file
    $schema = file_get_contents('../data/schema.sql');
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
        }
    }
    echo "Tables created successfully\n";
    
    // Read and execute the data file
    $data = file_get_contents('../data/insert_data.sql');
    $statements = array_filter(array_map('trim', explode(';', $data)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
        }
    }
    echo "Sample data inserted successfully\n";
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
?> 