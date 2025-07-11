<?php
require_once 'php/config.php';
$pageTitle = 'Checkout';
$currentPage = 'checkout';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';
$success = '';

try {
    $conn = getDBConnection();
    
    // Get cart items
    $query = "SELECT c.*, p.name, p.price, p.image, p.stock 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate totals
    $subtotal = 0;
    $totalItems = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $totalItems += $item['quantity'];
    }
    
    // Get user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $address = sanitizeInput($_POST['address'] ?? '');
        $city = sanitizeInput($_POST['city'] ?? '');
        $state = sanitizeInput($_POST['state'] ?? '');
        $zip = sanitizeInput($_POST['zip'] ?? '');
        $paymentMethod = sanitizeInput($_POST['payment_method'] ?? '');
        
        if (empty($name) || empty($email) || empty($phone) || empty($address) || 
            empty($city) || empty($state) || empty($zip) || empty($paymentMethod)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            try {
                $conn->beginTransaction();
                
                // Create order
                $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, shipping_address, 
                                      shipping_city, shipping_state, shipping_zip, payment_method) 
                                      VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $subtotal, $address, $city, $state, $zip, $paymentMethod]);
                $orderId = $conn->lastInsertId();
                
                // Add order items
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                      VALUES (?, ?, ?, ?)");
                foreach ($cartItems as $item) {
                    $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
                    
                    // Update product stock
                    $newStock = $item['stock'] - $item['quantity'];
                    $stmt2 = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
                    $stmt2->execute([$newStock, $item['product_id']]);
                }
                
                // Clear cart
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                
                $conn->commit();
                
                // Send order confirmation email
                $subject = "Order Confirmation - Order #" . $orderId;
                $message = "Dear " . $user['name'] . ",\n\n";
                $message .= "Thank you for your order. Your order details are as follows:\n\n";
                $message .= "Order Number: " . $orderId . "\n";
                $message .= "Total Amount: $" . number_format($subtotal, 2) . "\n\n";
                $message .= "Items:\n";
                foreach ($cartItems as $item) {
                    $message .= "- " . $item['name'] . " x " . $item['quantity'] . "\n";
                }
                $message .= "\nShipping Address:\n";
                $message .= $address . "\n";
                $message .= $city . ", " . $state . " " . $zip . "\n\n";
                $message .= "We will process your order shortly.\n\n";
                $message .= "Best regards,\n" . SITE_NAME;
                
                sendEmail($user['email'], $subject, $message);
                
                // Redirect to order confirmation page
                redirect('order-confirmation.php?id=' . $orderId);
            } catch (Exception $e) {
                $conn->rollBack();
                $error = "An error occurred while processing your order. Please try again later.";
            }
        }
    }
} catch (Exception $e) {
    $error = "An error occurred. Please try again later.";
}

include 'includes/header.php';
?>

<!-- Checkout Section -->
<section class="py-5">
    <div class="container">
        <h1 class="mb-4">Checkout</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (empty($cartItems)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some items to your cart to continue shopping</p>
                <a href="products.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Shipping Information</h5>
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <input type="text" class="form-control" id="state" name="state" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="zip" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" id="zip" name="zip" required>
                                    </div>
                                </div>
                                
                                <h5 class="card-title mb-4 mt-4">Payment Method</h5>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="credit_card" value="credit_card" checked>
                                        <label class="form-check-label" for="credit_card">
                                            Credit Card
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="paypal" value="paypal">
                                        <label class="form-check-label" for="paypal">
                                            PayPal
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Place Order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items (<?php echo $totalItems; ?>):</span>
                                <span>$<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong>$<?php echo number_format($subtotal, 2); ?></strong>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Order Items</h6>
                                    <?php foreach ($cartItems as $item): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                            </div>
                                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 