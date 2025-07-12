<?php
require_once 'php/config.php';
$pageTitle = 'Hire Tools';
$currentPage = 'hired-tools';

// Get filter parameters
$category_filter = $_GET['category'] ?? '';
$search_query = $_GET['search'] ?? '';

try {
    $conn = getDBConnection();
    
    // Build query for available tools
    $where_conditions = ["p.is_available = 1"];
    $params = [];
    
    if ($category_filter) {
        $where_conditions[] = "p.category_id = ?";
        $params[] = $category_filter;
    }
    
    if ($search_query) {
        $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search_query%";
        $params[] = "%$search_query%";
    }
    
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
    
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              $where_clause 
              ORDER BY p.name";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $availableTools = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get categories for filter
    $categories_query = "SELECT id, name FROM categories ORDER BY name";
    $categories_stmt = $conn->prepare($categories_query);
    $categories_stmt->execute();
    $categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If user is logged in, get their hired tools for the "My Hires" section
    if (isLoggedIn()) {
        $user_hires_query = "SELECT ht.*, p.name as product_name, p.image 
                            FROM hired_tools ht 
                            JOIN products p ON ht.product_id = p.id 
                            WHERE ht.user_id = ? 
                            ORDER BY ht.created_at DESC";
        $user_hires_stmt = $conn->prepare($user_hires_query);
        $user_hires_stmt->execute([$_SESSION['user_id']]);
        $userHiredTools = $user_hires_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (Exception $e) {
    $error = "An error occurred while fetching tools.";
}

include 'includes/header.php';
?>

<!-- Available Tools Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Hire Tools</h1>
            <?php if (isAdmin()): ?>
                <a href="admin-hired-tools.php" class="btn btn-primary">Manage Hired Tools</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Tools</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search tools...">
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="hired-tools.php" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Guest Registration Notice -->
        <?php if (!isLoggedIn()): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Want to hire tools?</strong> Please 
                <a href="register.php" class="alert-link">register</a> or 
                <a href="login.php" class="alert-link">login</a> to book tools.
            </div>
        <?php endif; ?>
        
        <!-- Available Tools -->
        <?php if (empty($availableTools)): ?>
            <div class="text-center py-5">
                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                <h3>No Tools Available</h3>
                <p class="text-muted">No tools match your current filters</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($availableTools as $tool): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if (!empty($tool['image'])): ?>
                                <img src="<?php echo htmlspecialchars($tool['image']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($tool['name']); ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-tools fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($tool['name']); ?></h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($tool['category_name'] ?? 'Uncategorized'); ?>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars($tool['description'] ?? 'No description available'); ?></p>
                                <div class="mb-3">
                                    <p class="mb-1">
                                        <strong>Daily Rate:</strong> 
                                        $<?php echo number_format($tool['price'], 2); ?>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Stock:</strong> 
                                        <?php echo $tool['stock']; ?> available
                                    </p>
                                </div>
                                
                                <?php if (isLoggedIn()): ?>
                                    <button class="btn btn-primary w-100" 
                                            onclick="showBookingModal(<?php echo $tool['id']; ?>, '<?php echo htmlspecialchars($tool['name']); ?>', <?php echo $tool['price']; ?>)">
                                        <i class="fas fa-calendar-plus"></i> Book This Tool
                                    </button>
                                <?php else: ?>
                                    <div class="d-grid gap-2">
                                        <a href="login.php" class="btn btn-primary">
                                            <i class="fas fa-sign-in-alt"></i> Login to Book
                                        </a>
                                        <a href="register.php" class="btn btn-outline-primary">
                                            <i class="fas fa-user-plus"></i> Register
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- My Hired Tools Section (for logged-in users) -->
<?php if (isLoggedIn() && !empty($userHiredTools)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">My Hired Tools</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($userHiredTools as $tool): ?>
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
                            
                            <?php if ($tool['status'] === 'active'): ?>
                                <button class="btn btn-primary w-100" 
                                        onclick="requestReturn(<?php echo $tool['id']; ?>)">
                                    <i class="fas fa-undo"></i> Request Return
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Tool Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalTitle">Book Tool</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <input type="hidden" id="productId" name="product_id">
                    <input type="hidden" name="action" value="book_tool">
                    
                    <div class="mb-3">
                        <label class="form-label">Tool</label>
                        <input type="text" class="form-control" id="toolName" readonly>
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
                    
                    <div class="mb-3">
                        <label class="form-label">Daily Rate</label>
                        <input type="text" class="form-control" id="dailyRate" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Total Price</label>
                        <input type="text" class="form-control" id="totalPrice" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitBooking()">Book Tool</button>
            </div>
        </div>
    </div>
</div>

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
// Booking functionality
function showBookingModal(productId, toolName, dailyRate) {
    document.getElementById('productId').value = productId;
    document.getElementById('toolName').value = toolName;
    document.getElementById('dailyRate').value = '$' + dailyRate.toFixed(2);
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    document.getElementById('hireDate').value = today;
    document.getElementById('returnDate').value = tomorrowStr;
    
    // Calculate initial price
    calculateBookingPrice();
    
    new bootstrap.Modal(document.getElementById('bookingModal')).show();
}

function calculateBookingPrice() {
    const hireDate = new Date(document.getElementById('hireDate').value);
    const returnDate = new Date(document.getElementById('returnDate').value);
    const dailyRate = parseFloat(document.getElementById('dailyRate').value.replace('$', ''));
    
    if (hireDate && returnDate && returnDate > hireDate) {
        const days = Math.ceil((returnDate - hireDate) / (1000 * 60 * 60 * 24));
        const totalPrice = dailyRate * days;
        document.getElementById('totalPrice').value = '$' + totalPrice.toFixed(2);
    } else {
        document.getElementById('totalPrice').value = '$0.00';
    }
}

function submitBooking() {
    const form = document.getElementById('bookingForm');
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

// Admin functions
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

// Auto-calculate booking price when dates change
document.addEventListener('DOMContentLoaded', function() {
    const hireDateInput = document.getElementById('hireDate');
    const returnDateInput = document.getElementById('returnDate');
    
    if (hireDateInput && returnDateInput) {
        hireDateInput.addEventListener('change', calculateBookingPrice);
        returnDateInput.addEventListener('change', calculateBookingPrice);
    }
});
</script>

<?php include 'includes/footer.php'; ?> 