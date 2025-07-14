<?php
require_once 'php/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo "User not logged in. Session data: ";
    print_r($_SESSION);
    exit;
}

echo "User ID: " . $_SESSION['user_id'] . "<br>";

try {
    $conn = getDBConnection();
    
    // Check if cart table exists and has data
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    
    echo "Items in cart: " . $result['count'] . "<br>";
    
    // Show all cart items for this user
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll();
    
    echo "<h3>Cart Items:</h3>";
    if (empty($cartItems)) {
        echo "No items in cart<br>";
    } else {
        foreach ($cartItems as $item) {
            echo "Product: " . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "<br>";
        }
    }
    
    // Test adding an item to cart
    if (isset($_GET['test_add'])) {
        $product_id = 1; // Test with product ID 1
        $quantity = 1;
        
        // Check if product exists
        $stmt = $conn->prepare("SELECT id, name FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            echo "<h3>Testing add to cart:</h3>";
            echo "Adding product: " . $product['name'] . "<br>";
            
            // Check if already in cart
            $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
            $cart_item = $stmt->fetch();
            
            if ($cart_item) {
                // Update quantity
                $new_quantity = $cart_item['quantity'] + $quantity;
                $stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$new_quantity, $cart_item['id']]);
                echo "Updated existing cart item. New quantity: " . $new_quantity . "<br>";
            } else {
                // Add new item
                $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
                echo "Added new item to cart<br>";
            }
            
            echo "Test completed. <a href='test_cart.php'>Refresh to see updated cart</a><br>";
        } else {
            echo "Product not found<br>";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<br>
<a href="test_cart.php?test_add=1" class="btn btn-primary">Test Add to Cart</a>
<a href="cart.php" class="btn btn-secondary">View Cart</a> 