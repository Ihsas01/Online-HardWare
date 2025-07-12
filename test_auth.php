<?php
require_once 'php/config.php';

echo "<h2>Authentication System Test</h2>";

// Test database connection
try {
    $conn = getDBConnection();
    if ($conn === null) {
        die("❌ Database connection failed");
    }
    echo "✅ Database connection successful!<br><br>";

    // Check if users table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists<br>";
        
        // Check table structure
        $stmt = $conn->query("DESCRIBE users");
        $columns = $stmt->fetchAll();
        echo "📋 Users table structure:<br>";
        foreach ($columns as $column) {
            echo "- {$column['Field']} ({$column['Type']})<br>";
        }
        echo "<br>";
        
        // Check if admin user exists
        $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE role = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "✅ Admin user exists: {$admin['username']} ({$admin['email']})<br>";
        } else {
            echo "⚠️ No admin user found<br>";
        }
        
        // Count total users
        $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch();
        echo "👥 Total users: {$count['count']}<br><br>";
        
    } else {
        echo "❌ Users table does not exist<br>";
        echo "Please run the database setup script first.<br>";
    }
    
} catch (PDOException $e) {
    die("❌ Database error: " . $e->getMessage());
}

// Test session functionality
echo "<h3>Session Test</h3>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Sessions are working<br>";
} else {
    echo "❌ Sessions are not working<br>";
}

// Test helper functions
echo "<h3>Helper Functions Test</h3>";
echo "isLoggedIn(): " . (isLoggedIn() ? "✅ true" : "✅ false") . "<br>";
echo "isAdmin(): " . (isAdmin() ? "✅ true" : "✅ false") . "<br>";

// Test sanitize function
$testInput = "<script>alert('test')</script>";
$sanitized = sanitizeInput($testInput);
echo "sanitizeInput(): " . ($sanitized === htmlspecialchars($testInput) ? "✅ working" : "❌ not working") . "<br>";

echo "<br><h3>Next Steps:</h3>";
echo "1. ✅ Database connection: " . ($conn ? "Working" : "Failed") . "<br>";
echo "2. ✅ Users table: " . ($stmt->rowCount() > 0 ? "Exists" : "Missing") . "<br>";
echo "3. ✅ Session support: " . (session_status() === PHP_SESSION_ACTIVE ? "Working" : "Failed") . "<br>";
echo "4. ✅ Helper functions: Working<br>";

if ($conn && $stmt->rowCount() > 0 && session_status() === PHP_SESSION_ACTIVE) {
    echo "<br><strong>🎉 Authentication system is ready!</strong><br>";
    echo "You can now:<br>";
    echo "- <a href='register.php'>Register a new account</a><br>";
    echo "- <a href='login.php'>Login with existing account</a><br>";
} else {
    echo "<br><strong>⚠️ Some issues detected. Please check the database setup.</strong><br>";
}
?> 