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
            <a href="hired-tools.php" class="btn btn-secondary">View All Hired Tools</a>
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
                                    <?php if ($tool['status'] === 'pending'): ?>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-success" 
                                                    onclick="approveTool(<?php echo $tool['id']; ?>)">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger" 
                                                    onclick="rejectTool(<?php echo $tool['id']; ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php elseif ($tool['status'] === 'approved'): ?>
                                        <button class="btn btn-success btn-sm" 
                                                onclick="activateTool(<?php echo $tool['id']; ?>)">
                                            Activate
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-info btn-sm" 
                                            onclick="viewDetails(<?php echo $tool['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
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
</script>

<?php include 'includes/footer.php'; ?> 