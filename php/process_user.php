<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Admin access required']);
    exit;
}

$conn = getDBConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_user':
        handleAddUser($conn);
        break;
    case 'edit_user':
        handleEditUser($conn);
        break;
    case 'delete_user':
        handleDeleteUser($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function handleAddUser($conn) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? 'customer';
    
    if (!$username || !$email || !$password || !$first_name || !$last_name) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    try {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Username already exists']);
            return;
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already exists']);
            return;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password, first_name, last_name, phone, address, role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name, $phone, $address, $role]);

        echo json_encode([
            'success' => true,
            'message' => 'User added successfully'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleEditUser($conn) {
    $user_id = $_POST['user_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? '';
    
    if (!$user_id || !$username || !$email || !$first_name || !$last_name || !$role) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    try {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        if (!$stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        // Check if username already exists (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $user_id]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Username already exists']);
            return;
        }

        // Check if email already exists (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already exists']);
            return;
        }

        // Build update query
        if ($password) {
            // Update with new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("
                UPDATE users 
                SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, phone = ?, address = ?, role = ?
                WHERE id = ?
            ");
            $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name, $phone, $address, $role, $user_id]);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("
                UPDATE users 
                SET username = ?, email = ?, first_name = ?, last_name = ?, phone = ?, address = ?, role = ?
                WHERE id = ?
            ");
            $stmt->execute([$username, $email, $first_name, $last_name, $phone, $address, $role, $user_id]);
        }

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update user']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDeleteUser($conn) {
    $user_id = $_POST['user_id'] ?? '';
    
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing user ID']);
        return;
    }

    try {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            http_response_code(400);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        // Prevent deleting the current admin user
        if ($user_id == $_SESSION['user_id']) {
            http_response_code(400);
            echo json_encode(['error' => 'Cannot delete your own account']);
            return;
        }

        // Check if this is the last admin (prevent deleting all admins)
        if ($user['role'] === 'admin') {
            $stmt = $conn->prepare("SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'");
            $stmt->execute();
            $admin_count = $stmt->fetch()['admin_count'];
            
            if ($admin_count <= 1) {
                http_response_code(400);
                echo json_encode(['error' => 'Cannot delete the last admin user']);
                return;
            }
        }

        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete user']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?> 