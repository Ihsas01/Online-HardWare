<?php
require_once 'php/config.php';
$pageTitle = 'Hired Tools';
$currentPage = 'hired-tools';

if (!isLoggedIn()) {
    redirect('login.php');
}

try {
    $conn = getDBConnection();
    
    if (isAdmin()) {
        // Admin view: Show all hired tools
        $query = "SELECT ht.*, p.name as product_name, p.image, u.username, u.first_name, u.last_name, u.email
                  FROM hired_tools ht 
                  JOIN products p ON ht.product_id = p.id 
                  JOIN users u ON ht.user_id = u.id
                  ORDER BY ht.created_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $hiredTools = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Customer view: Show only user's hired tools
        $query = "SELECT ht.*, p.name as product_name, p.image 
                  FROM hired_tools ht 
                  JOIN products p ON ht.product_id = p.id 
                  WHERE ht.user_id = ? 
                  ORDER BY ht.created_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute([$_SESSION['user_id']]);
        $hiredTools = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $error = "An error occurred while fetching hired tools.";
}

include 'includes/header.php';
?>

<!-- Hired Tools Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?php echo isAdmin() ? 'All Hired Tools' : 'My Hired Tools'; ?></h1>
            <?php if (isAdmin()): ?>
                <a href="admin-hired-tools.php" class="btn btn-primary">Manage Hired Tools</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (empty($hiredTools)): ?>
            <div class="text-center py-5">
                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                <h3>No Hired Tools</h3>
                <p class="text-muted">
                    <?php echo isAdmin() ? 'No tools have been hired yet' : 'You haven\'t hired any tools yet'; ?>
                </p>
                <?php if (!isAdmin()): ?>
                    <a href="products.php" class="btn btn-primary">Browse Tools</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($hiredTools as $tool): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if (!empty($tool['image'])): ?>
                                <img src="<?php echo htmlspecialchars($tool['image']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($tool['product_name']); ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-tools fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($tool['product_name']); ?></h5>
                                
                                <?php if (isAdmin()): ?>
                                    <p class="text-muted mb-2">
                                        <strong>Customer:</strong> 
                                        <?php echo htmlspecialchars($tool['first_name'] . ' ' . $tool['last_name']); ?>
                                        (<?php echo htmlspecialchars($tool['email']); ?>)
                                    </p>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <p class="mb-1">
                                        <strong>Hire Date:</strong> 
                                        <?php echo date('M d, Y', strtotime($tool['hire_date'])); ?>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Return Date:</strong> 
                                        <?php echo date('M d, Y', strtotime($tool['return_date'])); ?>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Total Price:</strong> 
                                        $<?php echo number_format($tool['total_price'], 2); ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Status:</strong> 
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
                                    </p>
                                    <?php if ($tool['admin_notes']): ?>
                                        <p class="mb-0 mt-2">
                                            <strong>Admin Notes:</strong> 
                                            <small class="text-muted"><?php echo htmlspecialchars($tool['admin_notes']); ?></small>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (isAdmin() && $tool['status'] === 'pending'): ?>
                                    <div class="btn-group w-100" role="group">
                                        <button class="btn btn-success" 
                                                onclick="approveTool(<?php echo $tool['id']; ?>)">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger" 
                                                onclick="rejectTool(<?php echo $tool['id']; ?>)">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                <?php elseif (!isAdmin() && $tool['status'] === 'active'): ?>
                                    <button class="btn btn-primary" 
                                            onclick="requestReturn(<?php echo $tool['id']; ?>)">
                                        Request Return
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Admin Approval Modal -->
<?php if (isAdmin()): ?>
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
<?php endif; ?>

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

function requestReturn(toolId) {
    if (confirm('Are you sure you want to request a return for this tool?')) {
        const formData = new FormData();
        formData.append('action', 'return_tool');
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
</script>

<?php include 'includes/footer.php'; ?> 