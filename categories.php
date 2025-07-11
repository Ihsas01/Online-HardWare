<?php
require_once 'php/config.php';
$pageTitle = 'Categories';
$currentPage = 'categories';

try {
    $conn = getDBConnection();
    
    // Get all categories with product counts
    $query = "SELECT c.*, COUNT(p.id) as product_count 
              FROM categories c 
              LEFT JOIN products p ON c.id = p.category_id 
              GROUP BY c.id 
              ORDER BY c.name";
    
    $categories = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "An error occurred while fetching categories.";
}

include 'includes/header.php';
?>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h1 class="mb-4">Product Categories</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Categories Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if (!empty($category['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($category['image_url']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($category['name']); ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas <?php echo !empty($category['icon']) ? htmlspecialchars($category['icon']) : 'fa-tools'; ?> fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars($category['description']); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">
                                    <?php echo $category['product_count']; ?> Products
                                </span>
                                <a href="products.php?category=<?php echo $category['id']; ?>" 
                                   class="btn btn-primary">
                                    View Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($categories)): ?>
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h3>No categories found</h3>
                <p class="text-muted">Please check back later for updates</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 