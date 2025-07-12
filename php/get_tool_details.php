<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo 'Access denied';
    exit;
}

$tool_id = $_GET['id'] ?? '';

if (!$tool_id) {
    http_response_code(400);
    echo 'Tool ID required';
    exit;
}

try {
    $conn = getDBConnection();
    
    $query = "SELECT ht.*, p.name as product_name, p.description as product_description, p.image, 
                     u.username, u.first_name, u.last_name, u.email, u.phone, u.address,
                     admin.first_name as admin_first_name, admin.last_name as admin_last_name
              FROM hired_tools ht 
              JOIN products p ON ht.product_id = p.id 
              JOIN users u ON ht.user_id = u.id
              LEFT JOIN users admin ON ht.admin_approved_by = admin.id
              WHERE ht.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$tool_id]);
    $tool = $stmt->fetch();
    
    if (!$tool) {
        http_response_code(404);
        echo 'Tool not found';
        exit;
    }
    
    // Calculate days hired
    $hire_start = new DateTime($tool['hire_date']);
    $hire_end = new DateTime($tool['return_date']);
    $days = $hire_end->diff($hire_start)->days;
    
} catch (Exception $e) {
    http_response_code(500);
    echo 'Database error';
    exit;
}
?>

<div class="row">
    <div class="col-md-6">
        <h6>Tool Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Tool Name:</strong></td>
                <td><?php echo htmlspecialchars($tool['product_name']); ?></td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td><?php echo htmlspecialchars($tool['product_description']); ?></td>
            </tr>
            <tr>
                <td><strong>Hire Period:</strong></td>
                <td><?php echo date('M d, Y', strtotime($tool['hire_date'])); ?> - <?php echo date('M d, Y', strtotime($tool['return_date'])); ?></td>
            </tr>
            <tr>
                <td><strong>Days Hired:</strong></td>
                <td><?php echo $days; ?> days</td>
            </tr>
            <tr>
                <td><strong>Total Price:</strong></td>
                <td>$<?php echo number_format($tool['total_price'], 2); ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
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
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6>Customer Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Name:</strong></td>
                <td><?php echo htmlspecialchars($tool['first_name'] . ' ' . $tool['last_name']); ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?php echo htmlspecialchars($tool['email']); ?></td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td><?php echo htmlspecialchars($tool['phone'] ?? 'Not provided'); ?></td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td><?php echo htmlspecialchars($tool['address'] ?? 'Not provided'); ?></td>
            </tr>
        </table>
        
        <?php if ($tool['admin_notes']): ?>
            <h6 class="mt-3">Admin Notes</h6>
            <p class="text-muted"><?php echo htmlspecialchars($tool['admin_notes']); ?></p>
        <?php endif; ?>
        
        <?php if ($tool['admin_approved_by']): ?>
            <h6 class="mt-3">Admin Action</h6>
            <p class="text-muted">
                <?php echo ucfirst($tool['status']); ?> by 
                <?php echo htmlspecialchars($tool['admin_first_name'] . ' ' . $tool['admin_last_name']); ?>
                on <?php echo date('M d, Y H:i', strtotime($tool['admin_approved_at'])); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($tool['image'])): ?>
    <div class="row mt-3">
        <div class="col-12">
            <h6>Tool Image</h6>
            <img src="<?php echo htmlspecialchars($tool['image']); ?>" 
                 class="img-fluid" style="max-height: 200px;" 
                 alt="<?php echo htmlspecialchars($tool['product_name']); ?>">
        </div>
    </div>
<?php endif; ?> 