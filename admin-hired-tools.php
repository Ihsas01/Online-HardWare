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

<!-- Admin Hired Tools Management Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Hired Tools</h1>
            <div>
                <button class="btn btn-success me-2" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> Add New Hired Tool
                </button>
                <a href="hired-tools.php" class="btn btn-secondary">View All Hired Tools</a>
            </div>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['total'] ?? 0; ?></h5>
                        <p class="card-text text-muted">Total</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['pending'] ?? 0; ?></h5>
                        <p class="card-text">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['approved'] ?? 0; ?></h5>
                        <p class="card-text">Approved</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['rejected'] ?? 0; ?></h5>
                        <p class="card-text">Rejected</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['active'] ?? 0; ?></h5>
                        <p class="card-text">Active</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['returned'] ?? 0; ?></h5>
                        <p class="card-text">Returned</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Filter</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="returned" <?php echo $status_filter === 'returned' ? 'selected' : ''; ?>>Returned</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date_filter" class="form-label">Date Filter</label>
                        <select class="form-select" id="date_filter" name="date_filter">
                            <option value="">All Time</option>
                            <option value="today" <?php echo $date_filter === 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo $date_filter === 'week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo $date_filter === 'month' ? 'selected' : ''; ?>>This Month</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="admin-hired-tools.php" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if (empty($hiredTools)): ?>
            <div class="text-center py-5">
                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                <h3>No Hired Tools Found</h3>
                <p class="text-muted">No tools match the current filters</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
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
                            <tr>
                                <td>#<?php echo $tool['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($tool['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($tool['image']); ?>" 
                                                 class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($tool['product_name']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($tool['first_name'] . ' ' . $tool['last_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($tool['email']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small><strong>From:</strong> <?php echo date('M d, Y', strtotime($tool['hire_date'])); ?></small><br>
                                        <small><strong>To:</strong> <?php echo date('M d, Y', strtotime($tool['return_date'])); ?></small>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($tool['total_price'], 2); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo match($tool['status']) {
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'rejected' => 'danger',
                                            'active' => 'success',
                                            'returned' => 'secondary',
                                            'cancelled' => 'dark',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($tool['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($tool['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?php if ($tool['status'] === 'pending'): ?>
                                            <button class="btn btn-success" 
                                                    onclick="approveTool(<?php echo $tool['id']; ?>)"
                                                    title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger" 
                                                    onclick="rejectTool(<?php echo $tool['id']; ?>)"
                                                    title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php elseif ($tool['status'] === 'approved'): ?>
                                            <button class="btn btn-success btn-sm" 
                                                    onclick="activateTool(<?php echo $tool['id']; ?>)"
                                                    title="Activate">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-warning btn-sm" 
                                                onclick="editTool(<?php echo $tool['id']; ?>)"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="deleteTool(<?php echo $tool['id']; ?>)"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <button class="btn btn-info btn-sm" 
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
</section>

<!-- Admin Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
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
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitApproval()">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Tool Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
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
<div class="modal fade" id="hiredToolModal" tabindex="-1">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitHiredToolForm()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
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
            alert('Error loading tool details');
        });
}

// New functions for add, edit, and delete
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
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading tool data');
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
}

function submitHiredToolForm() {
    const form = document.getElementById('hiredToolForm');
    const formData = new FormData(form);
    
    // Validate dates
    const hireDate = new Date(formData.get('hire_date'));
    const returnDate = new Date(formData.get('return_date'));
    
    if (returnDate <= hireDate) {
        alert('Return date must be after hire date');
        return;
    }
    
    fetch('php/process_hired_tool.php', {
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
</script>

<?php include 'includes/footer.php'; ?> 