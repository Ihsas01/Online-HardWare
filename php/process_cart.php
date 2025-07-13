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
    case 'add_to_cart':
        handleAddToCart($conn);
        break;
    case 'remove_from_cart':
        handleRemoveFromCart($conn);
        break;
    case 'update_cart_quantity':
        handleUpdateCartQuantity($conn);
        break;
    case 'clear_cart':
        handleClearCart($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function handleAddToCart($conn) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? '';
    $quantity = $_POST['quantity'] ?? 1;
    
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }

    try {
        // Check if product exists
        $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            http_response_code(400);
            echo json_encode(['error' => 'Product not found']);
            return;
        }

        // Check if already in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $cart_item = $stmt->fetch();

        if ($cart_item) {
            // Update quantity
            $new_quantity = $cart_item['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$new_quantity, $cart_item['id']]);
        } else {
            // Add new item
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleRemoveFromCart($conn) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? '';
    
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }

    try {
        // Remove from cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Product removed from cart'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Product not found in cart']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleUpdateCartQuantity($conn) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? '';
    $quantity = $_POST['quantity'] ?? 1;
    
    if (!$product_id || $quantity < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'Valid product ID and quantity are required']);
        return;
    }

    try {
        // Update cart quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $user_id, $product_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Cart quantity updated'
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Product not found in cart']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleClearCart($conn) {
    $user_id = $_SESSION['user_id'];

    try {
        // Clear all cart items for user
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?> 