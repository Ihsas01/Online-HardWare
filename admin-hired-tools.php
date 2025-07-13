<?php
require_once 'php/config.php';
$pageTitle = 'Admin - Manage Hired Tools';
$currentPage = 'admin-hired-tools';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

try {
    $conn = getDBConnection();
    
    // Get filter parameters
    $status_filter = $_GET['status'] ?? '';
    $date_filter = $_GET['date_filter'] ?? '';
    
    // Build query with filters
    $where_conditions = [];
    $params = [];
    
    if ($status_filter) {
        $where_conditions[] = "ht.status = ?";
        $params[] = $status_filter;
    }
    
    if ($date_filter) {
        switch ($date_filter) {
            case 'today':
                $where_conditions[] = "DATE(ht.created_at) = CURDATE()";
                break;
            case 'week':
                $where_conditions[] = "ht.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $where_conditions[] = "ht.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                break;
        }
    }
    
    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
    
    $query = "SELECT ht.*, p.name as product_name, p.image, u.username, u.first_name, u.last_name, u.email
              FROM hired_tools ht 
              JOIN products p ON ht.product_id = p.id 
              JOIN users u ON ht.user_id = u.id
              $where_clause
              ORDER BY ht.created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $hiredTools = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stats_query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                        SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned
                    FROM hired_tools";
    $stats_stmt = $conn->prepare($stats_query);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch();
    
} catch (Exception $e) {
    $error = "An error occurred while fetching hired tools.";
}

include 'includes/header.php';
?>

<!-- Hired Tools Hero Section -->
<section class="hired-hero">
    <div class="container">
        <div class="hired-hero-content">
            <h1 class="hired-hero-title">
                <i class="fas fa-tools me-3"></i>
                Manage Hired Tools
            </h1>
            <p class="hired-hero-subtitle">
                Manage tool rentals, approvals, and customer requests with ease.
            </p>
            <div class="hired-hero-actions">
                <button class="hired-btn hired-btn-success hired-interactive" onclick="showAddModal()">
                    <i class="fas fa-plus"></i>
                    Add New Hired Tool
                </button>
                <a href="hired-tools.php" class="hired-btn hired-btn-secondary hired-interactive">
                    <i class="fas fa-eye"></i>
                    View All Hired Tools
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Hired Tools Management Content -->
<section class="hired-management">
    <div class="hired-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger hired-interactive">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="hired-stats-grid">
            <div class="hired-stat-card total hired-interactive">
                <div class="hired-stat-content">
                    <div class="hired-stat-info">
                        <h3><?php echo number_format($stats['total'] ?? 0); ?></h3>
                        <p>Total Tools</p>
                    </div>
                    <div class="hired-stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
            
            <div class="hired-stat-card pending hired-interactive">
                <div class="hired-stat-content">
                    <div class="hired-stat-info">
                        <h3><?php echo number_format($stats['pending'] ?? 0); ?></h3>
                        <p>Pending</p>
                    </div>
                    <div class="hired-stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="hired-stat-card approved hired-interactive">
                <div class="hired-stat-content">
                    <div class="hired-stat-info">
                        <h3><?php echo number_format($stats['approved'] ?? 0); ?></h3>
                        <p>Approved</p>
                    </div>
                    <div class="hired-stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="hired-stat-card rejected hired-interactive">
                <div class="hired-stat-content">
                    <div class="hired-stat-info">
                        <h3><?php echo number_format($stats['rejected'] ?? 0); ?></h3>
                        <p>Rejected</p>
                    </div>
                    <div class="hired-stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="hired-stat-card active hired-interactive">
                <div class="hired-stat-content">
                    <div class="hired-stat-info">
                        <h3><?php echo number_format($stats['active'] ?? 0); ?></h3>
                        <p>Active</p>
                    </div>
                    <div class="hired-stat-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="hired-stat-card returned hired-interactive">
                <div class="hired-stat-content">
                    <div class="hired-stat-info">
                        <h3><?php echo number_format($stats['returned'] ?? 0); ?></h3>
                        <p>Returned</p>
                    </div>
                    <div class="hired-stat-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="hired-filters-card">
            <div class="hired-filters-header">
                <i class="fas fa-filter"></i>
                <h5>Search & Filter Tools</h5>
            </div>
            <div class="hired-filters-body">
                <form method="GET" class="hired-filters-form">
                    <div class="hired-form-group">
                        <label for="status" class="hired-form-label">Status Filter</label>
                        <select class="hired-form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="returned" <?php echo $status_filter === 'returned' ? 'selected' : ''; ?>>Returned</option>
                        </select>
                    </div>
                    
                    <div class="hired-form-group">
                        <label for="date_filter" class="hired-form-label">Date Filter</label>
                        <select class="hired-form-control" id="date_filter" name="date_filter">
                            <option value="">All Time</option>
                            <option value="today" <?php echo $date_filter === 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo $date_filter === 'week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo $date_filter === 'month' ? 'selected' : ''; ?>>This Month</option>
                        </select>
                    </div>
                    
                    <div class="hired-form-group">
                        <button type="submit" class="hired-btn hired-btn-primary">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>
                        <a href="admin-hired-tools.php" class="hired-btn hired-btn-secondary">
                            <i class="fas fa-times"></i>
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Hired Tools Table -->
        <div class="hired-table-card">
            <div class="hired-table-header">
                <div>
                    <i class="fas fa-table me-2"></i>
                    <h5>Hired Tools Management</h5>
                </div>
                <div>
                    <span class="badge bg-light text-dark"><?php echo count($hiredTools); ?> tools</span>
                </div>
            </div>
            <div class="hired-table-body">
                <?php if (empty($hiredTools)): ?>
                    <div class="hired-empty-state">
                        <div class="hired-empty-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="hired-empty-title">No Hired Tools Found</h3>
                        <p class="hired-empty-text">No tools match the current filters</p>
                        <button class="hired-btn hired-btn-success hired-interactive" onclick="showAddModal()">
                            <i class="fas fa-plus"></i>
                            Add First Tool
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="hired-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tool</th>
                                    <th>Customer</th>
                                    <th>Hire Period</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hiredTools as $tool): ?>
                                    <tr class="hired-interactive">
                                        <td><strong>#<?php echo $tool['id']; ?></strong></td>
                                        <td>
                                            <div class="hired-tool-info">
                                                <?php if (!empty($tool['image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($tool['image']); ?>" 
                                                         class="hired-tool-image" alt="Tool Image">
                                                <?php endif; ?>
                                                <span class="hired-tool-name"><?php echo htmlspecialchars($tool['product_name']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="hired-customer-info">
                                                <div class="hired-customer-name">
                                                    <?php echo htmlspecialchars($tool['first_name'] . ' ' . $tool['last_name']); ?>
                                                </div>
                                                <div class="hired-customer-email">
                                                    <?php echo htmlspecialchars($tool['email']); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="hired-hire-period">
                                                <div class="hired-hire-date">
                                                    <strong>From:</strong> <?php echo date('M d, Y', strtotime($tool['hire_date'])); ?>
                                                </div>
                                                <div class="hired-hire-date">
                                                    <strong>To:</strong> <?php echo date('M d, Y', strtotime($tool['return_date'])); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><strong>$<?php echo number_format($tool['total_price'], 2); ?></strong></td>
                                        <td>
                                            <span class="hired-badge <?php echo $tool['status']; ?>">
                                                <?php echo ucfirst($tool['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y H:i', strtotime($tool['created_at'])); ?></td>
                                        <td>
                                            <div class="hired-actions">
                                                <?php if ($tool['status'] === 'pending'): ?>
                                                    <button class="hired-action-btn approve hired-interactive" 
                                                            onclick="approveTool(<?php echo $tool['id']; ?>)"
                                                            title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="hired-action-btn reject hired-interactive" 
                                                            onclick="rejectTool(<?php echo $tool['id']; ?>)"
                                                            title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php elseif ($tool['status'] === 'approved'): ?>
                                                    <button class="hired-action-btn activate hired-interactive" 
                                                            onclick="activateTool(<?php echo $tool['id']; ?>)"
                                                            title="Activate">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button class="hired-action-btn edit hired-interactive" 
                                                        onclick="editTool(<?php echo $tool['id']; ?>)"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <button class="hired-action-btn delete hired-interactive" 
                                                        onclick="deleteTool(<?php echo $tool['id']; ?>)"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                
                                                <button class="hired-action-btn view hired-interactive" 
                                                        onclick="viewDetails(<?php echo $tool['id']; ?>)"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Admin Approval Modal -->
<div class="modal fade hired-modal" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Approve/Reject Tool</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="approvalForm">
                    <input type="hidden" id="hiredToolId" name="hired_tool_id">
                    <input type="hidden" id="approvalAction" name="action">
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="hired-btn hired-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="hired-btn hired-btn-primary hired-interactive" onclick="submitApproval()">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Tool Details Modal -->
<div class="modal fade hired-modal" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tool Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Hired Tool Modal -->
<div class="modal fade hired-modal" id="hiredToolModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hiredToolModalTitle">Add New Hired Tool</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="hiredToolForm">
                    <input type="hidden" id="hiredToolId" name="hired_tool_id">
                    <input type="hidden" id="formAction" name="action" value="add_hired_tool">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productId" class="form-label">Product *</label>
                                <select class="form-select" id="productId" name="product_id" required>
                                    <option value="">Select Product</option>
                                    <?php
                                    try {
                                        $products_query = "SELECT id, name, price FROM products WHERE is_available = 1 ORDER BY name";
                                        $products_stmt = $conn->prepare($products_query);
                                        $products_stmt->execute();
                                        $products = $products_stmt->fetchAll();
                                        
                                        foreach ($products as $product) {
                                            echo '<option value="' . $product['id'] . '" data-price="' . $product['price'] . '">' . 
                                                 htmlspecialchars($product['name']) . ' - $' . number_format($product['price'], 2) . '</option>';
                                        }
                                    } catch (Exception $e) {
                                        echo '<option value="">Error loading products</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userId" class="form-label">Customer *</label>
                                <select class="form-select" id="userId" name="user_id" required>
                                    <option value="">Select Customer</option>
                                    <?php
                                    try {
                                        $users_query = "SELECT id, username, first_name, last_name, email FROM users WHERE role = 'customer' ORDER BY first_name, last_name";
                                        $users_stmt = $conn->prepare($users_query);
                                        $users_stmt->execute();
                                        $users = $users_stmt->fetchAll();
                                        
                                        foreach ($users as $user) {
                                            $display_name = $user['first_name'] && $user['last_name'] ? 
                                                $user['first_name'] . ' ' . $user['last_name'] : $user['username'];
                                            echo '<option value="' . $user['id'] . '">' . 
                                                 htmlspecialchars($display_name) . ' (' . htmlspecialchars($user['email']) . ')</option>';
                                        }
                                    } catch (Exception $e) {
                                        echo '<option value="">Error loading users</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hireDate" class="form-label">Hire Date *</label>
                                <input type="date" class="form-control" id="hireDate" name="hire_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="returnDate" class="form-label">Return Date *</label>
                                <input type="date" class="form-control" id="returnDate" name="return_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="totalPrice" class="form-label">Total Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="totalPrice" name="total_price" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="active">Active</option>
                                    <option value="returned">Returned</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes</label>
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="hired-btn hired-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="hired-btn hired-btn-primary hired-interactive" onclick="submitHiredToolForm()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add the admin hired tools CSS -->
<link rel="stylesheet" href="css/admin-hired-tools.css">

<!-- JavaScript for enhanced hired tools interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation class
    const management = document.querySelector('.hired-management');
    if (management) {
        management.classList.add('hired-loading');
        setTimeout(() => {
            management.classList.add('loaded');
        }, 100);
    }
    
    // Add hover effects for interactive elements
    const interactiveElements = document.querySelectorAll('.hired-interactive');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Enhanced stat card animations
    const statCards = document.querySelectorAll('.hired-stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.hired-stat-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.hired-stat-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Table row hover effects
    const tableRows = document.querySelectorAll('.hired-table tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--hired-bg-light)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Action button hover effects
    const actionButtons = document.querySelectorAll('.hired-action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Form control focus effects
    const formControls = document.querySelectorAll('.hired-form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.borderColor = 'var(--hired-primary)';
            this.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.1)';
        });
        
        control.addEventListener('blur', function() {
            this.style.borderColor = 'var(--hired-border)';
            this.style.boxShadow = 'none';
        });
    });
});

function approveTool(toolId) {
    document.getElementById('hiredToolId').value = toolId;
    document.getElementById('approvalAction').value = 'approve_tool';
    document.getElementById('approvalModalTitle').textContent = 'Approve Tool';
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function rejectTool(toolId) {
    document.getElementById('hiredToolId').value = toolId;
    document.getElementById('approvalAction').value = 'reject_tool';
    document.getElementById('approvalModalTitle').textContent = 'Reject Tool';
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function activateTool(toolId) {
    if (confirm('Are you sure you want to activate this tool?')) {
        const formData = new FormData();
        formData.append('action', 'activate_tool');
        formData.append('hired_tool_id', toolId);
        
        fetch('php/process_hired_tool.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
}

function submitApproval() {
    const formData = new FormData(document.getElementById('approvalForm'));
    
    fetch('php/process_hired_tool.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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

function viewDetails(toolId) {
    // Load tool details via AJAX
    fetch(`php/get_tool_details.php?id=${toolId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('detailsModalBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading tool details', 'error');
        });
}

function showAddModal() {
    document.getElementById('hiredToolModalTitle').textContent = 'Add New Hired Tool';
    document.getElementById('formAction').value = 'add_hired_tool';
    document.getElementById('hiredToolId').value = '';
    document.getElementById('hiredToolForm').reset();
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('hireDate').value = today;
    
    new bootstrap.Modal(document.getElementById('hiredToolModal')).show();
}

function editTool(toolId) {
    // Load tool data for editing
    fetch(`php/get_hired_tool_details.php?id=${toolId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tool = data.data;
                
                document.getElementById('hiredToolModalTitle').textContent = 'Edit Hired Tool';
                document.getElementById('formAction').value = 'edit_hired_tool';
                document.getElementById('hiredToolId').value = tool.id;
                document.getElementById('productId').value = tool.product_id;
                document.getElementById('userId').value = tool.user_id;
                document.getElementById('hireDate').value = tool.hire_date;
                document.getElementById('returnDate').value = tool.return_date;
                document.getElementById('totalPrice').value = tool.total_price;
                document.getElementById('status').value = tool.status;
                document.getElementById('adminNotes').value = tool.admin_notes || '';
                
                new bootstrap.Modal(document.getElementById('hiredToolModal')).show();
            } else {
                showNotification('Error: ' + data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading tool data', 'error');
        });
}

function deleteTool(toolId) {
    if (confirm('Are you sure you want to delete this hired tool? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('action', 'delete_hired_tool');
        formData.append('hired_tool_id', toolId);
        
        fetch('php/process_hired_tool.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
}

function submitHiredToolForm() {
    const form = document.getElementById('hiredToolForm');
    const formData = new FormData(form);
    
    // Validate dates
    const hireDate = new Date(formData.get('hire_date'));
    const returnDate = new Date(formData.get('return_date'));
    
    if (returnDate <= hireDate) {
        showNotification('Return date must be after hire date', 'error');
        return;
    }
    
    fetch('php/process_hired_tool.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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

// Auto-calculate total price when dates or product changes
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('productId');
    const hireDateInput = document.getElementById('hireDate');
    const returnDateInput = document.getElementById('returnDate');
    const totalPriceInput = document.getElementById('totalPrice');
    
    function calculatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const hireDate = new Date(hireDateInput.value);
        const returnDate = new Date(returnDateInput.value);
        
        if (selectedOption && selectedOption.dataset.price && hireDateInput.value && returnDateInput.value && returnDate > hireDate) {
            const dailyPrice = parseFloat(selectedOption.dataset.price);
            const days = Math.ceil((returnDate - hireDate) / (1000 * 60 * 60 * 24));
            const totalPrice = dailyPrice * days;
            totalPriceInput.value = totalPrice.toFixed(2);
        }
    }
    
    productSelect.addEventListener('change', calculatePrice);
    hireDateInput.addEventListener('change', calculatePrice);
    returnDateInput.addEventListener('change', calculatePrice);
});

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

<?php include 'includes/footer.php'; ?> 