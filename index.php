<?php
require_once 'php/config.php';
$pageTitle = 'Home';
$currentPage = 'home';

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Welcome to I-I Brothers</h1>
                <p class="lead">Your one-stop shop for all hardware needs</p>
                <a href="products.php" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-md-6">
                <img src="images/hero-image.jpg" alt="Hardware Tools" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Featured Categories</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            try {
                $conn = getDBConnection();
                $query = "SELECT * FROM categories LIMIT 3";
                $categories = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($categories as $category) {
                    echo '<div class="col">';
                    echo '<div class="card h-100">';
                    if (!empty($category['image_url'])) {
                        echo '<img src="' . htmlspecialchars($category['image_url']) . '" class="card-img-top" alt="' . htmlspecialchars($category['name']) . '">';
                    } else {
                        echo '<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">';
                        echo '<i class="fas ' . (!empty($category['icon']) ? htmlspecialchars($category['icon']) : 'fa-tools') . ' fa-3x text-muted"></i>';
                        echo '</div>';
                    }
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($category['name']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars($category['description']) . '</p>';
                    echo '<a href="products.php?category=' . $category['id'] . '" class="btn btn-primary">View Products</a>';
                    echo '</div></div></div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12"><div class="alert alert-danger">Error loading categories</div></div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            try {
                $query = "SELECT * FROM products WHERE is_available = 1 LIMIT 4";
                $products = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($products as $product) {
                    echo '<div class="col">';
                    echo '<div class="card h-100">';
                    if (!empty($product['image'])) {
                        echo '<img src="' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">';
                    }
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
                    echo '<p class="card-text">$' . number_format($product['price'], 2) . '</p>';
                    echo '<a href="product.php?id=' . $product['id'] . '" class="btn btn-primary">View Details</a>';
                    echo '</div></div></div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12"><div class="alert alert-danger">Error loading products</div></div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Why Choose Us</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Fast Delivery</h5>
                        <p class="card-text">Quick and reliable delivery to your doorstep</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Quality Tools</h5>
                        <p class="card-text">Premium quality tools for all your needs</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">24/7 Support</h5>
                        <p class="card-text">Round the clock customer support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 