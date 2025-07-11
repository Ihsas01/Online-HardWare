<?php
require_once 'php/config.php';
$pageTitle = 'Order Details';
$currentPage = 'order-details';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';
$orderId = (int)($_GET['id'] ?? 0);

if (!$orderId) {
    redirect('orders.php');
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
        redirect('orders.php');
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

<!-- Order Details Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Order Details</h1>
            <a href="orders.php" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Orders
            </a>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title mb-0">Order #<?php echo $orderId; ?></h5>
                                <span class="badge bg-<?php 
                                    echo match($order['status']) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            
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
                            <h5 class="card-title">Order Status</h5>
                            <div class="timeline">
                                <?php
                                $statuses = [
                                    'pending' => 'Order Placed',
                                    'processing' => 'Processing',
                                    'shipped' => 'Shipped',
                                    'delivered' => 'Delivered'
                                ];
                                $currentStatus = array_search($order['status'], array_keys($statuses));
                                ?>
                                
                                <?php foreach ($statuses as $status => $label): ?>
                                    <?php
                                    $statusIndex = array_search($status, array_keys($statuses));
                                    $isActive = $statusIndex <= $currentStatus;
                                    $isCurrent = $status === $order['status'];
                                    ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker <?php echo $isActive ? 'bg-primary' : 'bg-light'; ?>">
                                            <?php if ($isActive): ?>
                                                <i class="fas fa-check text-white"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0 <?php echo $isActive ? '' : 'text-muted'; ?>">
                                                <?php echo $label; ?>
                                            </h6>
                                            <?php if ($isCurrent): ?>
                                                <small class="text-primary">Current Status</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if ($order['status'] === 'pending'): ?>
                                <div class="mt-4">
                                    <button class="btn btn-danger w-100" onclick="cancelOrder(<?php echo $orderId; ?>)">
                                        Cancel Order
                                    </button>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                                <h6>Need Help?</h6>
                                <p class="text-muted mb-2">If you have any questions about your order, please contact us.</p>
                                <a href="contact.php" class="btn btn-outline-primary w-100">Contact Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-content {
    padding-top: 2px;
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Add AJAX call to handle order cancellation
        console.log('Cancelling order:', orderId);
    }
}
</script>

<?php include 'includes/footer.php'; ?> 