<?php
require_once '../php/config.php';
$pageTitle = 'Admin Dashboard';
$currentPage = 'admin-dashboard';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

try {
    $conn = getDBConnection();
    
    // Get statistics
    $stats = [];
    
    // Total products
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_available = 1");
    $stats['products'] = $stmt->fetch()['count'];
    
    // Total users
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
    $stats['customers'] = $stmt->fetch()['count'];
    
    // Total orders
    $stmt = $conn->query("SELECT COUNT(*) as count FROM orders");
    $stats['orders'] = $stmt->fetch()['count'];
    
    // Total hired tools
    $stmt = $conn->query("SELECT COUNT(*) as count FROM hired_tools");
    $stats['hired_tools'] = $stmt->fetch()['count'];
    
    // Pending hired tools
    $stmt = $conn->query("SELECT COUNT(*) as count FROM hired_tools WHERE status = 'pending'");
    $stats['pending_hires'] = $stmt->fetch()['count'];
    
    // Recent orders
    $stmt = $conn->query("SELECT o.*, u.first_name, u.last_name 
                         FROM orders o 
                         JOIN users u ON o.user_id = u.id 
                         ORDER BY o.created_at DESC 
                         LIMIT 5");
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent hired tools
    $stmt = $conn->query("SELECT ht.*, p.name as product_name, u.first_name, u.last_name 
                         FROM hired_tools ht 
                         JOIN products p ON ht.product_id = p.id 
                         JOIN users u ON ht.user_id = u.id 
                         ORDER BY ht.created_at DESC 
                         LIMIT 5");
    $recentHires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Low stock products
    $stmt = $conn->query("SELECT * FROM products WHERE stock < 10 AND is_available = 1 ORDER BY stock ASC LIMIT 5");
    $lowStockProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Revenue statistics
    $stmt = $conn->query("SELECT 
                            SUM(total_amount) as total_revenue,
                            COUNT(*) as total_orders,
                            AVG(total_amount) as avg_order_value
                         FROM orders 
                         WHERE status = 'delivered'");
    $revenueStats = $stmt->fetch();
    
} catch (Exception $e) {
    $error = "An error occurred while loading dashboard data.";
}

include '../includes/header.php';
?>

<!-- Admin Dashboard Section -->
<section class="py-5">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-2">Admin Dashboard</h1>
                <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?>!</p>
            </div>
            <div class="btn-group">
                <a href="../admin-hired-tools.php" class="btn btn-primary">
                    <i class="fas fa-tools me-2"></i>Manage Hired Tools
                </a>
                <a href="../products.php" class="btn btn-outline-primary">
                    <i class="fas fa-box me-2"></i>Manage Products
                </a>
                <a href="../hired-tools.php" class="btn btn-outline-secondary">
                    <i class="fas fa-eye me-2"></i>View Hireable Tools
                </a>
            </div>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo number_format($stats['products'] ?? 0); ?></h4>
                                <p class="card-text">Total Products</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo number_format($stats['customers'] ?? 0); ?></h4>
                                <p class="card-text">Total Customers</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo number_format($stats['orders'] ?? 0); ?></h4>
                                <p class="card-text">Total Orders</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo number_format($stats['hired_tools'] ?? 0); ?></h4>
                                <p class="card-text">Hired Tools</p>
                                <?php if (($stats['pending_hires'] ?? 0) > 0): ?>
                                    <small class="badge bg-danger"><?php echo $stats['pending_hires']; ?> pending</small>
                                <?php endif; ?>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tools fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Revenue Statistics -->
        <?php if ($revenueStats && $revenueStats['total_revenue']): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>Revenue Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h3 class="text-primary">$<?php echo number_format($revenueStats['total_revenue'], 2); ?></h3>
                                    <p class="text-muted">Total Revenue</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h3 class="text-success"><?php echo number_format($revenueStats['total_orders']); ?></h3>
                                    <p class="text-muted">Total Orders</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h3 class="text-info">$<?php echo number_format($revenueStats['avg_order_value'], 2); ?></h3>
                                    <p class="text-muted">Average Order Value</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="../admin-hired-tools.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-tools me-2"></i>Manage Hired Tools
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="../products.php" class="btn btn-outline-success w-100">
                                    <i class="fas fa-box me-2"></i>Manage Products
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="../orders.php" class="btn btn-outline-info w-100">
                                    <i class="fas fa-shopping-cart me-2"></i>View Orders
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="../contact.php" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-envelope me-2"></i>Contact Messages
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Recent Orders
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentOrders)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['id']; ?></td>
                                                <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td>
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
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">No recent orders</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Hired Tools -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tools me-2"></i>Recent Hired Tools
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentHires)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tool</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentHires as $hire): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($hire['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($hire['first_name'] . ' ' . $hire['last_name']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo match($hire['status']) {
                                                            'pending' => 'warning',
                                                            'approved' => 'info',
                                                            'rejected' => 'danger',
                                                            'active' => 'success',
                                                            'returned' => 'secondary',
                                                            default => 'secondary'
                                                        };
                                                    ?>">
                                                        <?php echo ucfirst($hire['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($hire['status'] === 'pending'): ?>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-success" onclick="approveHire(<?php echo $hire['id']; ?>)">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button class="btn btn-danger" onclick="rejectHire(<?php echo $hire['id']; ?>)">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">No recent hired tools</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td>
                                                <span class="badge bg-danger"><?php echo $product['stock']; ?></span>
                                            </td>
                                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                                            <td>
                                                <a href="../product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quick Approval Modal -->
<div class="modal fade" id="quickApprovalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Quick Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickApprovalForm">
                    <input type="hidden" id="hireId" name="hired_tool_id">
                    <input type="hidden" id="approvalAction" name="action">
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitQuickApproval()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
function approveHire(hireId) {
    document.getElementById('hireId').value = hireId;
    document.getElementById('approvalAction').value = 'approve_tool';
    document.getElementById('approvalModalTitle').textContent = 'Approve Hire Request';
    new bootstrap.Modal(document.getElementById('quickApprovalModal')).show();
}

function rejectHire(hireId) {
    document.getElementById('hireId').value = hireId;
    document.getElementById('approvalAction').value = 'reject_tool';
    document.getElementById('approvalModalTitle').textContent = 'Reject Hire Request';
    new bootstrap.Modal(document.getElementById('quickApprovalModal')).show();
}

function submitQuickApproval() {
    const formData = new FormData(document.getElementById('quickApprovalForm'));
    
    fetch('../php/process_hired_tool.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<?php include '../includes/footer.php'; ?> 