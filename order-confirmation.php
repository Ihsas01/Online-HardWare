<?php
require_once 'php/config.php';
$pageTitle = 'Order Confirmation';
$currentPage = 'order-confirmation';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';
$orderId = (int)($_GET['id'] ?? 0);

if (!$orderId) {
    redirect('index.php');
}

try {
    $conn = getDBConnection();
    
    // Get order details
    $query = "SELECT o.*, u.name as user_name, u.email 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id = ? AND o.user_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$orderId, $_SESSION['user_id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        redirect('index.php');
    }
    
    // Get order items
    $query = "SELECT oi.*, p.name, p.image 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "An error occurred. Please try again later.";
}

include 'includes/header.php';
?>

<!-- Order Confirmation Section -->
<section class="py-5">
    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="text-center mb-5">
                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                <h1>Thank You for Your Order!</h1>
                <p class="lead">Your order has been placed successfully.</p>
                <p class="text-muted">Order #<?php echo $orderId; ?></p>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Order Details</h5>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Shipping Information</h6>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['user_name']); ?></p>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                    <p class="mb-1">
                                        <?php echo htmlspecialchars($order['shipping_city'] . ', ' . 
                                                                  $order['shipping_state'] . ' ' . 
                                                                  $order['shipping_zip']); ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Payment Information</h6>
                                    <p class="mb-1">Payment Method: <?php echo ucfirst($order['payment_method']); ?></p>
                                    <p class="mb-1">Order Status: <?php echo ucfirst($order['status']); ?></p>
                                    <p class="mb-1">Order Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                                </div>
                            </div>
                            
                            <h6 class="mb-3">Order Items</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($item['image'])): ?>
                                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                                 class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light me-3 d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="fas fa-tools text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">What's Next?</h5>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    A confirmation email has been sent to <?php echo htmlspecialchars($order['email']); ?>
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-truck text-primary me-2"></i>
                                    We'll notify you when your order ships
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-question-circle text-primary me-2"></i>
                                    Need help? <a href="contact.php">Contact us</a>
                                </li>
                            </ul>
                            <div class="d-grid gap-2">
                                <a href="products.php" class="btn btn-primary">Continue Shopping</a>
                                <a href="orders.php" class="btn btn-outline-primary">View All Orders</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 