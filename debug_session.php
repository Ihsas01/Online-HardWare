<?php
require_once 'php/config.php';

echo "<h2>Session Debug Information</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data: <pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Database Connection Test</h2>";
try {
    $conn = getDBConnection();
    if ($conn) {
        echo "Database connection successful<br>";
        
        // Test if cart table exists
        $stmt = $conn->query("SHOW TABLES LIKE 'cart'");
        if ($stmt->rowCount() > 0) {
            echo "Cart table exists<br>";
            
            // Check cart table structure
            $stmt = $conn->query("DESCRIBE cart");
            $columns = $stmt->fetchAll();
            echo "Cart table structure:<br>";
            foreach ($columns as $column) {
                echo "- " . $column['Field'] . " (" . $column['Type'] . ")<br>";
            }
            
            // Check if user is logged in and has cart items
            if (isLoggedIn()) {
                echo "<h3>User Cart Items</h3>";
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $result = $stmt->fetch();
                echo "Items in cart: " . $result['count'] . "<br>";
                
                if ($result['count'] > 0) {
                    $stmt = $conn->prepare("
                        SELECT c.*, p.name 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?
                    ");
                    $stmt->execute([$_SESSION['user_id']]);
                    $items = $stmt->fetchAll();
                    
                    echo "Cart items:<br>";
                    foreach ($items as $item) {
                        echo "- " . $item['name'] . " (Qty: " . $item['quantity'] . ")<br>";
                    }
                }
            } else {
                echo "User not logged in<br>";
            }
        } else {
            echo "Cart table does not exist<br>";
        }
    } else {
        echo "Database connection failed<br>";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

echo "<h2>Test Add to Cart</h2>";
if (isLoggedIn()) {
    echo "<form method='POST'>";
    echo "<input type='hidden' name='test_add' value='1'>";
    echo "<button type='submit'>Test Add Product 1 to Cart</button>";
    echo "</form>";
    
    if (isset($_POST['test_add'])) {
        try {
            $conn = getDBConnection();
            $user_id = $_SESSION['user_id'];
            $product_id = 1;
            $quantity = 1;
            
            // Check if product exists
            $stmt = $conn->prepare("SELECT id, name FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            
            if ($product) {
                echo "Adding product: " . $product['name'] . "<br>";
                
                // Check if already in cart
                $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$user_id, $product_id]);
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
                    $stmt->execute([$user_id, $product_id, $quantity]);
                    echo "Added new item to cart<br>";
                }
                
                echo "Test completed successfully!<br>";
            } else {
                echo "Product not found<br>";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        }
    }
} else {
    echo "Please log in to test cart functionality<br>";
}
?>

<br>
<a href="products.php">Back to Products</a>
<a href="cart.php">View Cart</a> 