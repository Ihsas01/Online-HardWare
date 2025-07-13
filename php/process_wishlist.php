<?php
require_once 'config.php';

if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Login required']);
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
    case 'add_to_wishlist':
        handleAddToWishlist($conn);
        break;
    case 'remove_from_wishlist':
        handleRemoveFromWishlist($conn);
        break;
    case 'clear_wishlist':
        handleClearWishlist($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function handleAddToWishlist($conn) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? '';
    
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }

    try {
        // Check if product exists
        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        if (!$stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Product not found']);
            return;
        }

        // Check if already in wishlist
        $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Product already in wishlist']);
            return;
        }

        // Add to wishlist
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $product_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Product added to wishlist'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleRemoveFromWishlist($conn) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? '';
    
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }

    try {
        // Remove from wishlist
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Product removed from wishlist'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Product not found in wishlist']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleClearWishlist($conn) {
    $user_id = $_SESSION['user_id'];

    try {
        // Clear all wishlist items for user
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
        $stmt->execute([$user_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Wishlist cleared successfully'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?> 