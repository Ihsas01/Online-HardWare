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
    $categories = $conn->query("SELECT c.*, (
        SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.is_available = 1
    ) as product_count FROM categories c ORDER BY c.name")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get price range for filter
    $priceRange = $conn->query("SELECT MIN(price) as min_price, MAX(price) as max_price FROM products WHERE is_available = 1")->fetch(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = "An error occurred while fetching products.";
}

// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // Return only the products grid for AJAX requests
    echo '<div class="products-grid" id="productsGrid">';
    if (isset($error)) {
        echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>' . $error . '</div>';
    } else {
        if (!empty($products)) {
            echo '<div class="row g-4">';
            foreach ($products as $index => $product) {
                echo '<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="' . ($index * 100) . '">';
                echo '<div class="product-card">';
                echo '<div class="product-image">';
                if (!empty($product['image'])) {
                    echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
                } else {
                    echo '<div class="product-placeholder"><i class="fas fa-tools"></i></div>';
                }
                echo '<div class="product-overlay">';
                echo '<div class="product-actions">';
                echo '<a href="product.php?id=' . $product['id'] . '" class="btn btn-primary btn-sm"><i class="fas fa-eye me-1"></i>View</a>';
                echo '<button class="btn btn-outline-light btn-sm quick-view-btn" data-product-id="' . $product['id'] . '"><i class="fas fa-search me-1"></i>Quick</button>';
                echo '</div>';
                echo '</div>';
                echo '<div class="product-badges">';
                echo '<span class="badge bg-success">In Stock</span>';
                if ($product['price'] < 50) {
                    echo '<span class="badge bg-warning">Best Value</span>';
                }
                echo '</div>';
                echo '</div>';
                echo '<div class="product-content">';
                echo '<div class="product-category"><i class="fas fa-tag me-1"></i>' . htmlspecialchars($product['category_name']) . '</div>';
                echo '<h5 class="product-title">' . htmlspecialchars($product['name']) . '</h5>';
                echo '<p class="product-description">' . htmlspecialchars(substr($product['description'], 0, 80)) . '...</p>';
                echo '<div class="product-rating">';
                echo '<div class="stars">';
                echo '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>';
                echo '</div>';
                echo '<span class="rating-text">(4.5)</span>';
                echo '</div>';
                echo '<div class="product-footer">';
                echo '<div class="product-price">$' . number_format($product['price'], 2) . '</div>';
                echo '<div class="product-actions-footer">';
                echo '<a href="product.php?id=' . $product['id'] . '" class="btn btn-primary btn-sm">View Details</a>';
                if (isLoggedIn()) {
                    echo '<button class="btn btn-success btn-sm hire-tool-btn" onclick="hireTool(' . $product['id'] . ', \'' . addslashes($product['name']) . '\', ' . $product['price'] . ')"><i class="fas fa-calendar-alt"></i> Hire</button>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div></div>';
            }
            echo '</div>';
        } else {
            echo '<div class="empty-state" data-aos="fade-up">';
            echo '<div class="empty-icon"><i class="fas fa-box-open"></i></div>';
            echo '<h3 class="empty-title">No products found</h3>';
            echo '<p class="empty-subtitle">';
            if ($searchQuery) {
                echo 'No products match your search "' . htmlspecialchars($searchQuery) . '"';
            } else {
                echo 'Please check back later for updates';
            }
            echo '</p>';
            echo '<div class="empty-actions">';
            echo '<a href="products.php" class="btn btn-primary"><i class="fas fa-refresh me-2"></i>Clear Filters</a>';
            echo '<a href="contact.php" class="btn btn-outline-primary"><i class="fas fa-envelope me-2"></i>Contact Us</a>';
            echo '</div>';
            echo '</div>';
        }
    }
    echo '</div>';
    exit; // Stop execution for AJAX requests
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
                                <a href="products.php" 
                                   class="category-item <?php echo !$categoryId ? 'active' : ''; ?>"
                                   data-category-id=""
                                   data-category-name="All Products">
                                    <div class="category-icon">
                                        <i class="fas fa-th-large"></i>
                                    </div>
                                    <div class="category-info">
                                        <span class="category-name">All Products</span>
                                        <small class="category-count"><?php echo count($products); ?> items</small>
                                    </div>
                                </a>
                                <?php foreach ($categories as $category): ?>
                                    <a href="products.php?category=<?php echo $category['id']; ?>"
                                       class="category-item <?php echo $categoryId === $category['id'] ? 'active' : ''; ?>"
                                       data-category-id="<?php echo $category['id']; ?>"
                                       data-category-name="<?php echo htmlspecialchars($category['name']); ?>">
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
                                <a href="#" class="quick-action-item" data-sort="price" data-order="asc">
                                    <i class="fas fa-sort-amount-down"></i>
                                    <span>Lowest Price</span>
                                </a>
                                <a href="#" class="quick-action-item" data-sort="price" data-order="desc">
                                    <i class="fas fa-sort-amount-up"></i>
                                    <span>Highest Price</span>
                                </a>
                                <a href="#" class="quick-action-item" id="clearFilters">
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
                            <h4 class="results-title" id="currentCategoryTitle">
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
                            <p class="results-subtitle text-muted" id="resultsCount">
                                <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?> found
                                <?php if ($searchQuery): ?>
                                    for "<?php echo htmlspecialchars($searchQuery); ?>"
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="results-info">
                                <span class="results-count" id="resultsCountSpan"><?php echo count($products); ?> items</span>
                                <span class="results-sort">Sorted by <?php echo ucfirst($sortBy); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="products-grid" data-aos="fade-up" data-aos-delay="200" id="productsGrid">
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
                                                        <button class="btn btn-success btn-sm hire-tool-btn" 
                                                                onclick="hireTool(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                                                            <i class="fas fa-calendar-alt"></i> Hire
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

    // Function to update products by category
    function updateProductsByCategory(categoryId, categoryName) {
        // Show loading state
        const productsGrid = document.getElementById('productsGrid');
        productsGrid.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        // Build the AJAX URL
        let url = 'products.php?ajax=1';
        if (categoryId) {
            url += '&category=' + categoryId;
        }
        // Get current search and sort parameters
        const currentUrl = new URL(window.location);
        const searchQuery = currentUrl.searchParams.get('search');
        const sortBy = currentUrl.searchParams.get('sort');
        const sortOrder = currentUrl.searchParams.get('order');
        if (searchQuery) url += '&search=' + encodeURIComponent(searchQuery);
        if (sortBy) url += '&sort=' + sortBy;
        if (sortOrder) url += '&order=' + sortOrder;
        // Fetch products
        fetch(url)
            .then(response => response.text())
            .then(html => {
                // Extract products HTML from response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newProductsGrid = doc.getElementById('productsGrid');
                if (newProductsGrid) {
                    productsGrid.innerHTML = newProductsGrid.innerHTML;
                    // Update results header
                    const currentCategoryTitle = document.getElementById('currentCategoryTitle');
                    const resultsCount = document.getElementById('resultsCount');
                    const resultsCountSpan = document.getElementById('resultsCountSpan');
                    if (currentCategoryTitle) {
                        currentCategoryTitle.textContent = categoryName;
                    }
                    // Update counts
                    const productCount = newProductsGrid.querySelectorAll('.product-card').length;
                    if (resultsCount) {
                        resultsCount.textContent = productCount + ' product' + (productCount !== 1 ? 's' : '') + ' found';
                    }
                    if (resultsCountSpan) {
                        resultsCountSpan.textContent = productCount + ' items';
                    }
                    // Reinitialize AOS for new content
                    AOS.refresh();
                    // Re-attach category listeners for new sidebar
                    // attachCategoryListeners(); // This function is no longer needed
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                productsGrid.innerHTML = '<div class="alert alert-danger">Error loading products. Please try again.</div>';
            });
    }

    // Quick actions functionality
    document.querySelectorAll('.quick-action-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const sort = this.getAttribute('data-sort');
            const order = this.getAttribute('data-order');
            
            if (sort && order) {
                // Update URL and reload with new sort
                const url = new URL(window.location);
                url.searchParams.set('sort', sort);
                url.searchParams.set('order', order);
                window.location.href = url.toString();
            } else if (this.id === 'clearFilters') {
                // Clear all filters
                window.location.href = 'products.php';
            }
        });
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

    // Add to cart functionality
    function addToCart(productId) {
        console.log('Adding product to cart:', productId);
        const formData = new FormData();
        formData.append('action', 'add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', 1);
        
        console.log('Sending request to php/process_cart.php');
        
        fetch('php/process_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showNotification('Product added to cart successfully!', 'success');
                // Optionally refresh the page or update cart count
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
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

    // Hire tool functionality
    function hireTool(productId, productName, price) {
        // Set modal values
        document.getElementById('hireProductName').textContent = productName;
        document.getElementById('hireProductPrice').textContent = '$' + price.toFixed(2);
        document.getElementById('productId').value = productId;
        document.getElementById('dailyPrice').value = price;
        
        // Set default dates
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        document.getElementById('hireDate').value = today.toISOString().split('T')[0];
        document.getElementById('returnDate').value = tomorrow.toISOString().split('T')[0];
        
        // Calculate initial total
        calculateHireTotal();
        
        // Show modal
        new bootstrap.Modal(document.getElementById('hireToolModal')).show();
    }

    function calculateHireTotal() {
        const hireDate = new Date(document.getElementById('hireDate').value);
        const returnDate = new Date(document.getElementById('returnDate').value);
        const dailyPrice = parseFloat(document.getElementById('dailyPrice').value);
        
        if (hireDate && returnDate && dailyPrice) {
            const days = Math.ceil((returnDate - hireDate) / (1000 * 60 * 60 * 24));
            const total = days * dailyPrice;
            document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
            document.getElementById('totalDays').textContent = days + ' day' + (days !== 1 ? 's' : '');
        }
    }

    function submitHireRequest() {
        const formData = new FormData(document.getElementById('hireForm'));
        formData.append('action', 'book_tool');
        
        // Show loading state
        const submitBtn = document.getElementById('submitHireBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        fetch('php/process_hired_tool.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('hireToolModal')).hide();
                // Optionally redirect to hired tools page
                window.location.href = 'hired-tools.php';
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // CATEGORY SIDEBAR AJAX HANDLER
    document.querySelectorAll('.category-item').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.getAttribute('data-category-id');
            const categoryName = this.getAttribute('data-category-name');
            // Update products grid via AJAX
            updateProductsByCategory(categoryId, categoryName);
            // Update URL without scrolling
            let url = 'products.php';
            if (categoryId) url += '?category=' + encodeURIComponent(categoryId);
            window.history.pushState({}, '', url);
            // Update active class
            document.querySelectorAll('.category-item').forEach(function(l) { l.classList.remove('active'); });
            this.classList.add('active');
        });
    });
</script>

<!-- Hire Tool Modal -->
<div class="modal fade" id="hireToolModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Hire Tool
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="hireForm">
                    <input type="hidden" id="productId" name="product_id">
                    <input type="hidden" id="dailyPrice" name="daily_price">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Tool Information</h6>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 id="hireProductName" class="card-title"></h6>
                                    <p class="card-text">
                                        <strong>Daily Rate:</strong> <span id="hireProductPrice"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Hire Details</h6>
                            <div class="mb-3">
                                <label for="hireDate" class="form-label">Hire Date</label>
                                <input type="date" class="form-control" id="hireDate" name="hire_date" 
                                       onchange="calculateHireTotal()" required>
                            </div>
                            <div class="mb-3">
                                <label for="returnDate" class="form-label">Return Date</label>
                                <input type="date" class="form-control" id="returnDate" name="return_date" 
                                       onchange="calculateHireTotal()" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Duration:</strong> <span id="totalDays">1 day</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total Price:</strong> <span id="totalPrice">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Important:</strong> Your hire request will be reviewed by our admin team. 
                        You will be notified once your request is approved or rejected.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="submitHireBtn" onclick="submitHireRequest()">
                    <i class="fas fa-calendar-check me-2"></i>Submit Hire Request
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 