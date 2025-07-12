<?php
require_once 'config.php';

try {
    $conn = getDBConnection();
    
    // Check if columns already exist
    $checkColumns = $conn->query("SHOW COLUMNS FROM hired_tools LIKE 'admin_notes'");
    $adminNotesExists = $checkColumns->rowCount() > 0;
    
    $checkColumns = $conn->query("SHOW COLUMNS FROM hired_tools LIKE 'admin_approved_by'");
    $adminApprovedByExists = $checkColumns->rowCount() > 0;
    
    $checkColumns = $conn->query("SHOW COLUMNS FROM hired_tools LIKE 'admin_approved_at'");
    $adminApprovedAtExists = $checkColumns->rowCount() > 0;
    
    // Update status enum if needed
    $checkStatus = $conn->query("SHOW COLUMNS FROM hired_tools LIKE 'status'");
    $statusColumn = $checkStatus->fetch();
    
    if ($statusColumn && strpos($statusColumn['Type'], 'approved') === false) {
        // Update the status enum to include new statuses
        $conn->exec("ALTER TABLE hired_tools MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'active', 'returned', 'cancelled') DEFAULT 'pending'");
        echo "✓ Updated status enum to include new statuses\n";
    }
    
    // Add new columns if they don't exist
    if (!$adminNotesExists) {
        $conn->exec("ALTER TABLE hired_tools ADD COLUMN admin_notes TEXT AFTER status");
        echo "✓ Added admin_notes column\n";
    }
    
    if (!$adminApprovedByExists) {
        $conn->exec("ALTER TABLE hired_tools ADD COLUMN admin_approved_by INT AFTER admin_notes");
        echo "✓ Added admin_approved_by column\n";
    }
    
    if (!$adminApprovedAtExists) {
        $conn->exec("ALTER TABLE hired_tools ADD COLUMN admin_approved_at TIMESTAMP NULL AFTER admin_approved_by");
        echo "✓ Added admin_approved_at column\n";
    }
    
    // Add foreign key constraint if it doesn't exist
    try {
        $conn->exec("ALTER TABLE hired_tools ADD CONSTRAINT fk_hired_tools_admin_approved_by FOREIGN KEY (admin_approved_by) REFERENCES users(id)");
        echo "✓ Added foreign key constraint for admin_approved_by\n";
    } catch (Exception $e) {
        // Foreign key might already exist
        echo "ℹ Foreign key constraint already exists or not needed\n";
    }
    
    echo "\n✅ Database schema updated successfully!\n";
    echo "The hired tools system is now ready to use.\n";
    
} catch (Exception $e) {
    echo "❌ Error updating database schema: " . $e->getMessage() . "\n";
}
?> 