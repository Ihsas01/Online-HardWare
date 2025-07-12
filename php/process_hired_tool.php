<?php
require_once 'config.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
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
    case 'book_tool':
        handleBookTool($conn);
        break;
    case 'approve_tool':
        handleApproveTool($conn);
        break;
    case 'reject_tool':
        handleRejectTool($conn);
        break;
    case 'return_tool':
        handleReturnTool($conn);
        break;
    case 'activate_tool':
        handleActivateTool($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function handleBookTool($conn) {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    $product_id = $_POST['product_id'] ?? '';
    $hire_date = $_POST['hire_date'] ?? '';
    $return_date = $_POST['return_date'] ?? '';
    
    if (!$product_id || !$hire_date || !$return_date) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    try {
        // Check if product exists and is available
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND is_available = 1");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            http_response_code(400);
            echo json_encode(['error' => 'Product not available']);
            return;
        }

        // Check if tool is already hired for the requested dates
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count FROM hired_tools 
            WHERE product_id = ? AND status IN ('pending', 'approved', 'active')
            AND (
                (hire_date BETWEEN ? AND ?) OR
                (return_date BETWEEN ? AND ?) OR
                (hire_date <= ? AND return_date >= ?)
            )
        ");
        $stmt->execute([$product_id, $hire_date, $return_date, $hire_date, $return_date, $hire_date, $return_date]);
        $existing = $stmt->fetch();
        
        if ($existing['count'] > 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Tool is already hired for the selected dates']);
            return;
        }

        // Calculate total price
        $hire_start = new DateTime($hire_date);
        $hire_end = new DateTime($return_date);
        $days = $hire_end->diff($hire_start)->days;
        $total_price = $product['price'] * $days;

        // Insert hired tool record
        $stmt = $conn->prepare("
            INSERT INTO hired_tools (product_id, user_id, hire_date, return_date, total_price, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$product_id, $_SESSION['user_id'], $hire_date, $return_date, $total_price]);

        echo json_encode([
            'success' => true,
            'message' => 'Tool booking request submitted successfully. Waiting for admin approval.',
            'total_price' => $total_price
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleApproveTool($conn) {
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        return;
    }

    $hired_tool_id = $_POST['hired_tool_id'] ?? '';
    $admin_notes = $_POST['admin_notes'] ?? '';
    
    if (!$hired_tool_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing hired tool ID']);
        return;
    }

    try {
        $stmt = $conn->prepare("
            UPDATE hired_tools 
            SET status = 'approved', admin_notes = ?, admin_approved_by = ?, admin_approved_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$admin_notes, $_SESSION['user_id'], $hired_tool_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Tool booking approved successfully'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Hired tool not found']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleRejectTool($conn) {
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        return;
    }

    $hired_tool_id = $_POST['hired_tool_id'] ?? '';
    $admin_notes = $_POST['admin_notes'] ?? '';
    
    if (!$hired_tool_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing hired tool ID']);
        return;
    }

    try {
        $stmt = $conn->prepare("
            UPDATE hired_tools 
            SET status = 'rejected', admin_notes = ?, admin_approved_by = ?, admin_approved_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$admin_notes, $_SESSION['user_id'], $hired_tool_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Tool booking rejected'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Hired tool not found']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleReturnTool($conn) {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    $hired_tool_id = $_POST['hired_tool_id'] ?? '';
    
    if (!$hired_tool_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing hired tool ID']);
        return;
    }

    try {
        // Check if user owns this hired tool
        $stmt = $conn->prepare("
            SELECT * FROM hired_tools 
            WHERE id = ? AND user_id = ? AND status = 'active'
        ");
        $stmt->execute([$hired_tool_id, $_SESSION['user_id']]);
        $hired_tool = $stmt->fetch();
        
        if (!$hired_tool) {
            http_response_code(400);
            echo json_encode(['error' => 'Hired tool not found or not active']);
            return;
        }

        $stmt = $conn->prepare("
            UPDATE hired_tools 
            SET status = 'returned'
            WHERE id = ?
        ");
        $stmt->execute([$hired_tool_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Tool return request submitted successfully'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleActivateTool($conn) {
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        return;
    }

    $hired_tool_id = $_POST['hired_tool_id'] ?? '';
    
    if (!$hired_tool_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing hired tool ID']);
        return;
    }

    try {
        // Check if tool is approved and can be activated
        $stmt = $conn->prepare("
            SELECT * FROM hired_tools 
            WHERE id = ? AND status = 'approved'
        ");
        $stmt->execute([$hired_tool_id]);
        $hired_tool = $stmt->fetch();
        
        if (!$hired_tool) {
            http_response_code(400);
            echo json_encode(['error' => 'Hired tool not found or not approved']);
            return;
        }

        // Update status to active
        $stmt = $conn->prepare("
            UPDATE hired_tools 
            SET status = 'active'
            WHERE id = ?
        ");
        $stmt->execute([$hired_tool_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Tool activated successfully'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to activate tool']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?> 