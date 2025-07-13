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

<!-- Admin Dashboard Hero Section -->
<section class="admin-hero">
    <div class="container">
        <div class="admin-hero-content">
            <h1 class="admin-hero-title">
                <i class="fas fa-tachometer-alt me-3"></i>
                Admin Dashboard
            </h1>
            <p class="admin-hero-subtitle">
                Welcome back, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?>! 
                Here's your business overview and quick actions.
            </p>
            <div class="admin-hero-actions">
                <div class="btn-group" role="group">
                    <a href="../admin-hired-tools.php" class="btn btn-light btn-lg interactive">
                        <i class="fas fa-tools me-2"></i>Manage Hired Tools
                    </a>
                    <a href="../products.php" class="btn btn-outline-light btn-lg interactive">
                        <i class="fas fa-box me-2"></i>Manage Products
                    </a>
                    <a href="manage-users.php" class="btn btn-outline-light btn-lg interactive">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="../hired-tools.php" class="btn btn-outline-light btn-lg interactive">
                        <i class="fas fa-eye me-2"></i>View Hireable Tools
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admin Dashboard Content -->
<section class="admin-dashboard">
    <div class="admin-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger admin-interactive">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="admin-stats-grid">
            <div class="admin-stat-card products interactive">
                <div class="admin-stat-content">
                    <div class="admin-stat-info">
                        <h3><?php echo number_format($stats['products'] ?? 0); ?></h3>
                        <p>Total Products</p>
                    </div>
                    <div class="admin-stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
            
            <div class="admin-stat-card customers interactive">
                <div class="admin-stat-content">
                    <div class="admin-stat-info">
                        <h3><?php echo number_format($stats['customers'] ?? 0); ?></h3>
                        <p>Total Customers</p>
                    </div>
                    <div class="admin-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="admin-stat-card orders interactive">
                <div class="admin-stat-content">
                    <div class="admin-stat-info">
                        <h3><?php echo number_format($stats['orders'] ?? 0); ?></h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="admin-stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            
            <div class="admin-stat-card hired-tools interactive">
                <div class="admin-stat-content">
                    <div class="admin-stat-info">
                        <h3><?php echo number_format($stats['hired_tools'] ?? 0); ?></h3>
                        <p>Hired Tools</p>
                    </div>
                    <div class="admin-stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <?php if (($stats['pending_hires'] ?? 0) > 0): ?>
                    <div class="admin-stat-badge">
                        <?php echo $stats['pending_hires']; ?> pending
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Revenue Statistics -->
        <?php if ($revenueStats && $revenueStats['total_revenue']): ?>
        <div class="admin-revenue-card">
            <div class="admin-revenue-header">
                <i class="fas fa-chart-line"></i>
                <h5>Revenue Statistics</h5>
            </div>
            <div class="admin-revenue-body">
                <div class="admin-revenue-grid">
                    <div class="admin-revenue-item interactive">
                        <div class="admin-revenue-value text-primary">
                            $<?php echo number_format($revenueStats['total_revenue'], 2); ?>
                        </div>
                        <div class="admin-revenue-label">Total Revenue</div>
                    </div>
                    <div class="admin-revenue-item interactive">
                        <div class="admin-revenue-value text-success">
                            <?php echo number_format($revenueStats['total_orders']); ?>
                        </div>
                        <div class="admin-revenue-label">Total Orders</div>
                    </div>
                    <div class="admin-revenue-item interactive">
                        <div class="admin-revenue-value text-info">
                            $<?php echo number_format($revenueStats['avg_order_value'], 2); ?>
                        </div>
                        <div class="admin-revenue-label">Average Order Value</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Actions -->
        <div class="admin-actions-card">
            <div class="admin-actions-header">
                <i class="fas fa-bolt"></i>
                <h5>Quick Actions</h5>
            </div>
            <div class="admin-actions-body">
                <div class="admin-actions-grid">
                    <a href="../admin-hired-tools.php" class="admin-action-btn interactive">
                        <i class="fas fa-tools"></i>
                        <span>Manage Hired Tools</span>
                    </a>
                    <a href="../products.php" class="admin-action-btn interactive">
                        <i class="fas fa-box"></i>
                        <span>Manage Products</span>
                    </a>
                    <a href="manage-users.php" class="admin-action-btn interactive">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="../orders.php" class="admin-action-btn interactive">
                        <i class="fas fa-shopping-cart"></i>
                        <span>View Orders</span>
                    </a>
                    <a href="../contact.php" class="admin-action-btn interactive">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Messages</span>
                    </a>
                    <a href="../hired-tools.php" class="admin-action-btn interactive">
                        <i class="fas fa-eye"></i>
                        <span>View Hireable Tools</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="admin-activity-grid">
            <!-- Recent Orders -->
            <div class="admin-activity-card">
                <div class="admin-activity-header">
                    <i class="fas fa-shopping-cart"></i>
                    <h5>Recent Orders</h5>
                </div>
                <div class="admin-activity-body">
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="admin-table">
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
                                        <tr class="admin-interactive">
                                            <td><strong>#<?php echo $order['id']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                            <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                            <td>
                                                <span class="admin-badge <?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent orders</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Hired Tools -->
            <div class="admin-activity-card">
                <div class="admin-activity-header">
                    <i class="fas fa-tools"></i>
                    <h5>Recent Hired Tools</h5>
                </div>
                <div class="admin-activity-body">
                    <?php if (!empty($recentHires)): ?>
                        <div class="table-responsive">
                            <table class="admin-table">
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
                                        <tr class="admin-interactive">
                                            <td><strong><?php echo htmlspecialchars($hire['product_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($hire['first_name'] . ' ' . $hire['last_name']); ?></td>
                                            <td>
                                                <span class="admin-badge <?php echo $hire['status']; ?>">
                                                    <?php echo ucfirst($hire['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($hire['status'] === 'pending'): ?>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-success btn-sm admin-interactive" onclick="approveHire(<?php echo $hire['id']; ?>)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm admin-interactive" onclick="rejectHire(<?php echo $hire['id']; ?>)">
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
                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent hired tools</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="admin-alert-card">
            <div class="admin-alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h5>Low Stock Alert</h5>
            </div>
            <div class="admin-alert-body">
                <div class="table-responsive">
                    <table class="admin-table">
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
                                <tr class="admin-interactive">
                                    <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                    <td>
                                        <span class="admin-badge cancelled"><?php echo $product['stock']; ?></span>
                                    </td>
                                    <td><strong>$<?php echo number_format($product['price'], 2); ?></strong></td>
                                    <td>
                                        <a href="../product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary admin-interactive">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quick Approval Modal -->
<div class="modal fade admin-modal" id="quickApprovalModal" tabindex="-1">
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
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary admin-interactive" onclick="submitQuickApproval()">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Add the admin dashboard CSS -->
<link rel="stylesheet" href="../css/admin-dashboard.css">

<!-- JavaScript for enhanced dashboard interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation class
    const dashboard = document.querySelector('.admin-dashboard');
    if (dashboard) {
        dashboard.classList.add('admin-loading');
        setTimeout(() => {
            dashboard.classList.add('loaded');
        }, 100);
    }
    
    // Add hover effects for interactive elements
    const interactiveElements = document.querySelectorAll('.admin-interactive');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Enhanced stat card animations
    const statCards = document.querySelectorAll('.admin-stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.admin-stat-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.admin-stat-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Revenue item hover effects
    const revenueItems = document.querySelectorAll('.admin-revenue-item');
    revenueItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Action button hover effects
    const actionButtons = document.querySelectorAll('.admin-action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.2)';
            }
        });
        
        button.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1)';
            }
        });
    });
    
    // Table row hover effects
    const tableRows = document.querySelectorAll('.admin-table tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--admin-bg-light)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});

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
            // Show success message with animation
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification('Error: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInFromRight 0.3s ease;
    `;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutToRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInFromRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutToRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php include '../includes/footer.php'; ?> 