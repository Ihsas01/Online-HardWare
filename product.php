<?php
require_once 'php/config.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $conn = getDBConnection();
    
    // Get product details
    $stmt = $conn->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ?
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: products.php');
        exit;
    }

    // Get related products (same category)
    $stmt = $conn->prepare("
        SELECT * FROM products 
        WHERE category_id = ? AND id != ? 
        LIMIT 4
    ");
    $stmt->execute([$product['category_id'], $product_id]);
    $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = "An error occurred while fetching product details.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-tools"></i> I-I Brothers
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" href="hired-tools.php">Hired Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Details Section -->
    <section class="py-5">
        <div class="container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php else: ?>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                        <li class="breadcrumb-item">
                            <a href="products.php?category=<?php echo $product['category_id']; ?>">
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </li>
                    </ol>
                </nav>

                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-6 mb-4">
                        <?php if (isset($product['image']) && $product['image']): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                 class="img-fluid rounded" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 400px;">
                                <i class="fas fa-tools fa-5x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Info -->
                    <div class="col-md-6">
                        <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p class="text-muted mb-3">
                            Category: 
                            <a href="#" class="category-instant-link" data-category-id="<?php echo $product['category_id']; ?>">
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </a>
                        </p>
                        <h2 class="text-primary mb-4">$<?php echo number_format($product['price'], 2); ?></h2>
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>
                        <div class="mb-4">
                            <h5>Specifications</h5>
                            <ul class="list-unstyled">
                                <?php if (isset($product['brand']) && $product['brand']): ?>
                                    <li><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></li>
                                <?php endif; ?>
                                <?php if (isset($product['model']) && $product['model']): ?>
                                    <li><strong>Model:</strong> <?php echo htmlspecialchars($product['model']); ?></li>
                                <?php endif; ?>
                                <?php if (isset($product['sku']) && $product['sku']): ?>
                                    <li><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></li>
                                <?php endif; ?>
                                <?php if (isset($product['stock'])): ?>
                                    <li><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?> units available</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="d-grid gap-2">
                            <?php if (isLoggedIn()): ?>
                                <button class="btn btn-primary btn-lg" onclick="addToCart(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-danger" onclick="addToWishlist(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-heart"></i> Add to Wishlist
                                </button>
                                <button class="btn btn-success" onclick="hireTool(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                                    <i class="fas fa-calendar-alt"></i> Hire This Tool
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Login to Purchase
                                </a>
                                <a href="login.php" class="btn btn-outline-primary">
                                    <i class="fas fa-heart"></i> Login to Add to Wishlist
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <?php if (!empty($related_products)): ?>
                    <div class="mt-5">
                        <h3 class="mb-4">Related Products</h3>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                            <?php foreach ($related_products as $related): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <?php if (isset($related['image']) && $related['image']): ?>
                                            <img src="<?php echo htmlspecialchars($related['image']); ?>" 
                                                 class="card-img-top" 
                                                 alt="<?php echo htmlspecialchars($related['name']); ?>">
                                        <?php else: ?>
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="fas fa-tools fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($related['name']); ?></h5>
                                            <p class="card-text text-primary">
                                                $<?php echo number_format($related['price'], 2); ?>
                                            </p>
                                            <a href="product.php?id=<?php echo $related['id']; ?>" 
                                               class="btn btn-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>I-I Brothers</h5>
                    <p>Your trusted hardware store since 1995</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="contact.php" class="text-light">Contact</a></li>
                        <li><a href="privacy.php" class="text-light">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone"></i> 076 xxx xxxx</li>
                        <li><i class="fas fa-envelope"></i> mohamedihsas001@gmail.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Main Street, Colombo, Sri Lanka</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> I-I Brothers. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    
    <!-- Product Page JavaScript -->
    <script>
    // Add to cart functionality
    function addToCart(productId) {
        const formData = new FormData();
        formData.append('action', 'add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', 1);
        
        fetch('php/process_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to cart successfully!', 'success');
            } else {
                showNotification('Error: ' + data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    // Add to wishlist functionality
    function addToWishlist(productId) {
        const formData = new FormData();
        formData.append('action', 'add_to_wishlist');
        formData.append('product_id', productId);
        
        fetch('php/process_wishlist.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to wishlist!', 'success');
            } else {
                showNotification('Error: ' + data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    // Hire tool functionality
    function hireTool(productId, productName, price) {
        // Redirect to hired tools page with product info
        window.location.href = `hired-tools.php?product_id=${productId}&product_name=${encodeURIComponent(productName)}&price=${price}`;
    }

    // Show notification function
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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const catLink = document.querySelector('.category-instant-link');
    if (catLink) {
        catLink.addEventListener('click', function(e) {
            e.preventDefault();
            const catId = this.getAttribute('data-category-id');
            // Try to find a products grid on this page
            let productsGrid = document.getElementById('productsGrid');
            if (productsGrid) {
                productsGrid.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                fetch('products.php?ajax=1&category=' + encodeURIComponent(catId))
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('productsGrid');
                        if (newGrid) {
                            productsGrid.innerHTML = newGrid.innerHTML;
                            productsGrid.scrollIntoView({behavior: 'smooth'});
                        } else {
                            window.location.href = 'products.php?category=' + encodeURIComponent(catId);
                        }
                    })
                    .catch(() => {
                        window.location.href = 'products.php?category=' + encodeURIComponent(catId);
                    });
            } else {
                window.location.href = 'products.php?category=' + encodeURIComponent(catId);
            }
        });
    }
});
</script>
</body>
</html> 