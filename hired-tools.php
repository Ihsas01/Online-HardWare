<?php
require_once 'php/config.php';
$pageTitle = 'Hired Tools';
$currentPage = 'hired-tools';

if (!isLoggedIn()) {
    redirect('login.php');
}

try {
    $conn = getDBConnection();
    
    // Get user's hired tools
    $query = "SELECT ht.*, p.name as product_name, p.image 
              FROM hired_tools ht 
              JOIN products p ON ht.product_id = p.id 
              WHERE ht.user_id = ? 
              ORDER BY ht.created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $hiredTools = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "An error occurred while fetching hired tools.";
}

include 'includes/header.php';
?>

<!-- Hired Tools Section -->
<section class="py-5">
    <div class="container">
        <h1 class="mb-4">My Hired Tools</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (empty($hiredTools)): ?>
            <div class="text-center py-5">
                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                <h3>No Hired Tools</h3>
                <p class="text-muted">You haven't hired any tools yet</p>
                <a href="products.php" class="btn btn-primary">Browse Tools</a>
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
                                                'active' => 'success',
                                                'returned' => 'info',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo ucfirst($tool['status']); ?>
                                        </span>
                                    </p>
                                </div>
                                <?php if ($tool['status'] === 'active'): ?>
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

<script>
function requestReturn(toolId) {
    if (confirm('Are you sure you want to request a return for this tool?')) {
        // Add AJAX call to handle return request
        console.log('Return requested for tool:', toolId);
    }
}
</script>

<?php include 'includes/footer.php'; ?> 