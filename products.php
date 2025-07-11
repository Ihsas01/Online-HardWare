<?php
require_once 'php/config.php';
$pageTitle = 'Products';
$currentPage = 'products';

try {
    $conn = getDBConnection();
    
    // Get category filter
    $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
    
    // Get search query
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    // Get sort parameter
    $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'name';
    $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';
    
    // Build query
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.is_available = 1";
    $params = [];
    
    if ($categoryId) {
        $query .= " AND p.category_id = ?";
        $params[] = $categoryId;
    }
    
    if ($searchQuery) {
        $query .= " AND (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)";
        $searchParam = "%$searchQuery%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    // Add sorting
    $allowedSortFields = ['name', 'price', 'category_name'];
    $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
    $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';
    $query .= " ORDER BY p.$sortBy $sortOrder";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get categories for filter
    $categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get price range for filter
    $priceRange = $conn->query("SELECT MIN(price) as min_price, MAX(price) as max_price FROM products WHERE is_available = 1")->fetch(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = "An error occurred while fetching products.";
}

include 'includes/header.php';
?>

<!-- Hero Banner -->
<section class="products-hero py-5 bg-gradient-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="hero-badge mb-3">
                    <span class="badge bg-white-gradient">Our Products</span>
                </div>
                <h1 class="hero-title text-white mb-4">
                    Discover Our
                    <span class="text-gradient">Premium Tools</span>
                </h1>
                <p class="hero-subtitle text-white-70 mb-4">
                    Explore our comprehensive collection of high-quality hardware and tools. 
                    From professional equipment to DIY essentials, we have everything you need.
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($products); ?>+</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($categories); ?>+</div>
                        <div class="stat-label">Categories</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="hero-visual">
                    <div class="floating-tools">
                        <div class="tool-icon tool-1">
                            <i class="fas fa-hammer"></i>
                        </div>
                        <div class="tool-icon tool-2">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <div class="tool-icon tool-3">
                            <i class="fas fa-screwdriver"></i>
                        </div>
                        <div class="tool-icon tool-4">
                            <i class="fas fa-drill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="search-filter-section py-4 bg-white border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <form class="search-form" method="GET" action="products.php">
                    <?php if ($categoryId): ?>
                        <input type="hidden" name="category" value="<?php echo $categoryId; ?>">
                    <?php endif; ?>
                    <div class="input-group search-input-group">
                        <span class="input-group-text search-icon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control search-input" 
                               name="search" 
                               placeholder="Search products, categories, or descriptions..."
                               value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <button class="btn btn-primary search-btn" type="submit">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="view-controls d-flex justify-content-lg-end gap-2">
                    <button class="btn btn-outline-primary view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="btn btn-outline-primary view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-sort"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?sort=name&order=asc<?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">Name A-Z</a></li>
                            <li><a class="dropdown-item" href="?sort=name&order=desc<?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">Name Z-A</a></li>
                            <li><a class="dropdown-item" href="?sort=price&order=asc<?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">Price Low to High</a></li>
                            <li><a class="dropdown-item" href="?sort=price&order=desc<?php echo $categoryId ? '&category=' . $categoryId : ''; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">Price High to Low</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <!-- Enhanced Categories Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar" data-aos="fade-right" data-aos-duration="800">
                    <div class="filter-card">
                        <div class="filter-header">
                            <h5 class="filter-title">
                                <i class="fas fa-filter me-2"></i>Categories
                            </h5>
                        </div>
                        <div class="filter-content">
                            <div class="category-filter">
                                <a href="products.php<?php echo $searchQuery ? '?search=' . urlencode($searchQuery) : ''; ?>" 
                                   class="category-item <?php echo !$categoryId ? 'active' : ''; ?>">
                                    <div class="category-icon">
                                        <i class="fas fa-th-large"></i>
                                    </div>
                                    <div class="category-info">
                                        <span class="category-name">All Products</span>
                                        <small class="category-count"><?php echo count($products); ?> items</small>
                                    </div>
                                </a>
                                <?php foreach ($categories as $category): ?>
                                    <a href="products.php?category=<?php echo $category['id']; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>" 
                                       class="category-item <?php echo $categoryId === $category['id'] ? 'active' : ''; ?>">
                                        <div class="category-icon">
                                            <i class="fas <?php echo !empty($category['icon']) ? htmlspecialchars($category['icon']) : 'fa-tools'; ?>"></i>
                                        </div>
                                        <div class="category-info">
                                            <span class="category-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                            <small class="category-count"><?php echo $category['product_count'] ?? '0'; ?> items</small>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-card mt-4">
                        <div class="filter-header">
                            <h5 class="filter-title">
                                <i class="fas fa-dollar-sign me-2"></i>Price Range
                            </h5>
                        </div>
                        <div class="filter-content">
                            <div class="price-range">
                                <div class="price-inputs">
                                    <input type="number" class="form-control" placeholder="Min" id="minPrice">
                                    <span class="price-separator">-</span>
                                    <input type="number" class="form-control" placeholder="Max" id="maxPrice">
                                </div>
                                <button class="btn btn-primary btn-sm w-100 mt-2" id="applyPriceFilter">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="filter-card mt-4">
                        <div class="filter-header">
                            <h5 class="filter-title">
                                <i class="fas fa-bolt me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="filter-content">
                            <div class="quick-actions">
                                <a href="products.php?sort=price&order=asc" class="quick-action-item">
                                    <i class="fas fa-sort-amount-down"></i>
                                    <span>Lowest Price</span>
                                </a>
                                <a href="products.php?sort=price&order=desc" class="quick-action-item">
                                    <i class="fas fa-sort-amount-up"></i>
                                    <span>Highest Price</span>
                                </a>
                                <a href="products.php" class="quick-action-item">
                                    <i class="fas fa-refresh"></i>
                                    <span>Clear Filters</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Products Grid -->
            <div class="col-lg-9">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" data-aos="fade-up">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Results Header -->
                <div class="results-header mb-4" data-aos="fade-up">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="results-title">
                                <?php if ($categoryId): ?>
                                    <?php 
                                    $currentCategory = array_filter($categories, function($cat) use ($categoryId) {
                                        return $cat['id'] == $categoryId;
                                    });
                                    $currentCategory = reset($currentCategory);
                                    echo htmlspecialchars($currentCategory['name'] ?? 'Category');
                                    ?>
                                <?php else: ?>
                                    All Products
                                <?php endif; ?>
                            </h4>
                            <p class="results-subtitle text-muted">
                                <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?> found
                                <?php if ($searchQuery): ?>
                                    for "<?php echo htmlspecialchars($searchQuery); ?>"
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="results-info">
                                <span class="results-count"><?php echo count($products); ?> items</span>
                                <span class="results-sort">Sorted by <?php echo ucfirst($sortBy); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="products-grid" data-aos="fade-up" data-aos-delay="200">
                    <?php if (!empty($products)): ?>
                        <div class="row g-4">
                            <?php foreach ($products as $index => $product): ?>
                                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                                    <div class="product-card">
                                        <div class="product-image">
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            <?php else: ?>
                                                <div class="product-placeholder">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="product-overlay">
                                                <div class="product-actions">
                                                    <a href="product.php?id=<?php echo $product['id']; ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                    <button class="btn btn-outline-light btn-sm quick-view-btn" 
                                                            data-product-id="<?php echo $product['id']; ?>">
                                                        <i class="fas fa-search me-1"></i>Quick
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product-badges">
                                                <span class="badge bg-success">In Stock</span>
                                                <?php if ($product['price'] < 50): ?>
                                                    <span class="badge bg-warning">Best Value</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-category">
                                                <i class="fas fa-tag me-1"></i>
                                                <?php echo htmlspecialchars($product['category_name']); ?>
                                            </div>
                                            <h5 class="product-title">
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </h5>
                                            <p class="product-description">
                                                <?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?>
                                            </p>
                                            <div class="product-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-text">(4.5)</span>
                                            </div>
                                            <div class="product-footer">
                                                <div class="product-price">
                                                    $<?php echo number_format($product['price'], 2); ?>
                                                </div>
                                                <div class="product-actions-footer">
                                                    <a href="product.php?id=<?php echo $product['id']; ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        View Details
                                                    </a>
                                                    <?php if (isLoggedIn()): ?>
                                                        <button class="btn btn-outline-primary btn-sm add-to-cart-btn" 
                                                                onclick="addToCart(<?php echo $product['id']; ?>)">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state" data-aos="fade-up">
                            <div class="empty-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3 class="empty-title">No products found</h3>
                            <p class="empty-subtitle">
                                <?php if ($searchQuery): ?>
                                    No products match your search "<?php echo htmlspecialchars($searchQuery); ?>"
                                <?php else: ?>
                                    Please check back later for updates
                                <?php endif; ?>
                            </p>
                            <div class="empty-actions">
                                <a href="products.php" class="btn btn-primary">
                                    <i class="fas fa-refresh me-2"></i>Clear Filters
                                </a>
                                <a href="contact.php" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>Contact Us
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <div class="newsletter-badge mb-3">
                    <span class="badge bg-primary-gradient">Stay Updated</span>
                </div>
                <h2 class="newsletter-title">Get Product Updates</h2>
                <p class="newsletter-subtitle">
                    Be the first to know about new products, special offers, and exclusive deals
                </p>
                <div class="newsletter-form">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group newsletter-input-group">
                                <input type="email" class="form-control newsletter-input" placeholder="Enter your email address">
                                <button class="btn btn-primary newsletter-btn" type="button">
                                    <i class="fas fa-paper-plane me-2"></i>Subscribe
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">We respect your privacy. Unsubscribe at any time.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced JavaScript -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // View toggle functionality
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.getAttribute('data-view');
            const productsGrid = document.querySelector('.products-grid');
            
            if (view === 'list') {
                productsGrid.classList.add('list-view');
            } else {
                productsGrid.classList.remove('list-view');
            }
        });
    });

    // Price filter functionality
    document.getElementById('applyPriceFilter')?.addEventListener('click', function() {
        const minPrice = document.getElementById('minPrice').value;
        const maxPrice = document.getElementById('maxPrice').value;
        
        if (minPrice || maxPrice) {
            let url = new URL(window.location);
            if (minPrice) url.searchParams.set('min_price', minPrice);
            if (maxPrice) url.searchParams.set('max_price', maxPrice);
            window.location.href = url.toString();
        }
    });

    // Quick view functionality
    document.querySelectorAll('.quick-view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            // Add quick view modal functionality here
            console.log('Quick view for product:', productId);
        });
    });

    // Add to cart animation
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.add('btn-success');
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                this.classList.remove('btn-success');
            }, 2000);
        });
    });

    // Search form enhancement
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchForm.submit();
            }
        });
    }

    // Floating tools animation
    const floatingTools = document.querySelector('.floating-tools');
    if (floatingTools) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const tools = floatingTools.querySelectorAll('.tool-icon');
            
            tools.forEach((tool, index) => {
                const speed = 0.5 + (index * 0.1);
                tool.style.transform = `translateY(${scrolled * speed * 0.1}px) rotate(${scrolled * 0.01}deg)`;
            });
        });
    }

    // Newsletter form enhancement
    const newsletterBtn = document.querySelector('.newsletter-btn');
    const newsletterInput = document.querySelector('.newsletter-input');
    
    if (newsletterBtn && newsletterInput) {
        newsletterBtn.addEventListener('click', function() {
            if (newsletterInput.value.trim()) {
                this.innerHTML = '<i class="fas fa-check me-2"></i>Subscribed!';
                this.classList.add('btn-success');
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Subscribe';
                    this.classList.remove('btn-success');
                    newsletterInput.value = '';
                }, 3000);
            }
        });
    }

    // Intersection Observer for enhanced animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.product-card, .filter-card').forEach(el => {
        observer.observe(el);
    });
</script>

<?php include 'includes/footer.php'; ?> 