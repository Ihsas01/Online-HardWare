<?php
require_once 'config.php';

echo "Testing database connection...\n";
echo "Host: " . DB_HOST . "\n";
echo "Database: " . DB_NAME . "\n";
echo "Username: " . DB_USER . "\n";

try {
    // First, try to connect to MySQL server without database
    echo "\nStep 1: Testing MySQL server connection...\n";
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Successfully connected to MySQL server\n";
    
    // Check if database exists
    echo "\nStep 2: Checking if database exists...\n";
    $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
    $dbExists = $stmt->fetch();
    
    if ($dbExists) {
        echo "✓ Database '" . DB_NAME . "' exists\n";
        
        // Try to connect to the specific database
        echo "\nStep 3: Testing connection to database...\n";
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✓ Successfully connected to database\n";
        
        // Check if tables exist
        echo "\nStep 4: Checking tables...\n";
        $stmt = $conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✓ Found " . count($tables) . " tables:\n";
            foreach ($tables as $table) {
                echo "  - " . $table . "\n";
            }
        } else {
            echo "! No tables found in database\n";
        }
    } else {
        echo "! Database '" . DB_NAME . "' does not exist\n";
        echo "\nPlease run setup_database.php to create the database and tables.\n";
    }
    
} catch(PDOException $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting steps:\n";
    echo "1. Make sure XAMPP is running\n";
    echo "2. Check if MySQL service is started in XAMPP Control Panel\n";
    echo "3. Verify the database credentials in config.php\n";
    echo "4. Try running setup_database.php to create the database\n";
}
?> 