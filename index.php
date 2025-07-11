<?php
require_once 'php/config.php';
$pageTitle = 'Home';
$currentPage = 'home';

include 'includes/header.php';
?>

<!-- Hero Section with Dynamic Background -->
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-background">
        <div class="hero-particles"></div>
        <div class="hero-gradient"></div>
    </div>
    <div class="container position-relative z-index-2">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content" data-aos="fade-right" data-aos-duration="1200">
                    <div class="hero-badge mb-3" data-aos="fade-up" data-aos-delay="200">
                        <span class="badge bg-primary-gradient">Premium Hardware Solutions</span>
                    </div>
                    <h1 class="hero-title display-3 fw-bold text-white mb-4">
                        Professional
                        <span class="text-gradient">Hardware</span>
                        <br>Solutions
                    </h1>
                    <p class="hero-subtitle lead text-white-70 mb-5">
                        Discover premium tools and equipment for every project. From DIY enthusiasts to professional contractors, 
                        we provide the highest quality hardware solutions with exceptional service and competitive pricing.
                    </p>
                    <div class="hero-buttons">
                        <a href="products.php" class="btn btn-primary btn-lg me-3 mb-3 hero-btn">
                            <i class="fas fa-shopping-cart me-2"></i>Shop Now
                            <div class="btn-ripple"></div>
                        </a>
                        <a href="#featured-categories" class="btn btn-outline-light btn-lg mb-3 hero-btn">
                            <i class="fas fa-tools me-2"></i>Explore Categories
                        </a>
                    </div>
                    <div class="hero-stats mt-5" data-aos="fade-up" data-aos-delay="400">
                        <div class="row">
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number">500+</div>
                                    <div class="stat-label">Products</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number">10K+</div>
                                    <div class="stat-label">Happy Customers</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number">24/7</div>
                                    <div class="stat-label">Support</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="300">
                    <div class="floating-cards">
                        <div class="floating-card card-1">
                            <div class="card-icon">
                                <i class="fas fa-hammer"></i>
                            </div>
                            <div class="card-content">
                                <h6>Premium Tools</h6>
                                <p>Professional quality</p>
                            </div>
                        </div>
                        <div class="floating-card card-2">
                            <div class="card-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="card-content">
                                <h6>Fast Delivery</h6>
                                <p>Next day shipping</p>
                            </div>
                        </div>
                        <div class="floating-card card-3">
                            <div class="card-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="card-content">
                                <h6>Warranty</h6>
                                <p>Full protection</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <div class="scroll-arrow"></div>
        <span class="scroll-text">Scroll to explore</span>
    </div>
</section>

<!-- Features Bar with Enhanced Design -->
<section class="features-bar py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-shipping-fast feature-icon"></i>
                    </div>
                    <h6 class="feature-title">Free Shipping</h6>
                    <small class="text-muted">On orders over $50</small>
                    <div class="feature-hover"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-shield-alt feature-icon"></i>
                    </div>
                    <h6 class="feature-title">Quality Guarantee</h6>
                    <small class="text-muted">30-day return policy</small>
                    <div class="feature-hover"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-headset feature-icon"></i>
                    </div>
                    <h6 class="feature-title">Expert Support</h6>
                    <small class="text-muted">24/7 customer service</small>
                    <div class="feature-hover"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-credit-card feature-icon"></i>
                    </div>
                    <h6 class="feature-title">Secure Payment</h6>
                    <small class="text-muted">Multiple payment options</small>
                    <div class="feature-hover"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories with Enhanced Design -->
<section id="featured-categories" class="categories-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <div class="section-badge mb-3">
                <span class="badge bg-secondary-gradient">Our Categories</span>
            </div>
            <h2 class="section-title">Featured Categories</h2>
            <p class="section-subtitle text-muted">Explore our comprehensive range of hardware categories</p>
        </div>
        <div class="row g-4">
            <?php
            try {
                $conn = getDBConnection();
                $query = "SELECT * FROM categories LIMIT 6";
                $categories = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($categories as $index => $category) {
                    echo '<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="' . ($index * 150) . '">';
                    echo '<div class="category-card">';
                    echo '<div class="category-image">';
                    if (!empty($category['image_url'])) {
                        echo '<img src="' . htmlspecialchars($category['image_url']) . '" alt="' . htmlspecialchars($category['name']) . '">';
                    } else {
                        echo '<div class="category-placeholder">';
                        echo '<i class="fas ' . (!empty($category['icon']) ? htmlspecialchars($category['icon']) : 'fa-tools') . '"></i>';
                        echo '</div>';
                    }
                    echo '<div class="category-overlay"></div>';
                    echo '</div>';
                    echo '<div class="category-content">';
                    echo '<h5 class="category-title">' . htmlspecialchars($category['name']) . '</h5>';
                    echo '<p class="category-description">' . htmlspecialchars($category['description']) . '</p>';
                    echo '<a href="products.php?category=' . $category['id'] . '" class="category-link">';
                    echo '<span>Explore Products</span>';
                    echo '<i class="fas fa-arrow-right"></i>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div></div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12"><div class="alert alert-danger">Error loading categories</div></div>';
            }
            ?>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="categories.php" class="btn btn-outline-primary btn-lg">View All Categories</a>
        </div>
    </div>
</section>

<!-- Featured Products with Enhanced Design -->
<section class="products-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <div class="section-badge mb-3">
                <span class="badge bg-primary-gradient">Featured</span>
            </div>
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle text-muted">Handpicked quality products for your projects</p>
        </div>
        <div class="row g-4">
            <?php
            try {
                $query = "SELECT * FROM products WHERE is_available = 1 LIMIT 8";
                $products = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($products as $index => $product) {
                    echo '<div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="' . ($index * 100) . '">';
                    echo '<div class="product-card">';
                    echo '<div class="product-image">';
                    if (!empty($product['image'])) {
                        echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
                    } else {
                        echo '<div class="product-placeholder">';
                        echo '<i class="fas fa-tools"></i>';
                        echo '</div>';
                    }
                    echo '<div class="product-overlay">';
                    echo '<div class="product-actions">';
                    echo '<a href="product.php?id=' . $product['id'] . '" class="btn btn-primary btn-sm">View Details</a>';
                    echo '<button class="btn btn-outline-light btn-sm ms-2 quick-view-btn" data-product-id="' . $product['id'] . '">Quick View</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="product-badge">';
                    echo '<span class="badge bg-success">In Stock</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="product-content">';
                    echo '<h5 class="product-title">' . htmlspecialchars($product['name']) . '</h5>';
                    echo '<div class="product-price">$' . number_format($product['price'], 2) . '</div>';
                    echo '<div class="product-rating">';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star-half-alt"></i>';
                    echo '<span class="rating-text">(4.5)</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div></div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12"><div class="alert alert-danger">Error loading products</div></div>';
            }
            ?>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="products.php" class="btn btn-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>

<!-- Why Choose Us with Enhanced Design -->
<section class="why-choose-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <div class="section-badge mb-3">
                <span class="badge bg-info-gradient">Why Choose Us</span>
            </div>
            <h2 class="section-title">Why Choose I-I Brothers</h2>
            <p class="section-subtitle text-muted">We're committed to providing the best hardware solutions</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5 class="feature-title">Fast Delivery</h5>
                    <p class="feature-description">Quick and reliable delivery to your doorstep with real-time tracking</p>
                    <div class="feature-hover"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h5 class="feature-title">Quality Tools</h5>
                    <p class="feature-description">Premium quality tools from trusted brands for all your needs</p>
                    <div class="feature-hover"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="feature-title">24/7 Support</h5>
                    <p class="feature-description">Round the clock customer support with expert assistance</p>
                    <div class="feature-hover"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="feature-title">Warranty</h5>
                    <p class="feature-description">Comprehensive warranty on all products for your peace of mind</p>
                    <div class="feature-hover"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section with Enhanced Design -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <div class="section-badge mb-3">
                <span class="badge bg-warning-gradient">Testimonials</span>
            </div>
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-subtitle text-muted">Real feedback from satisfied customers</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Excellent quality tools and fast delivery. The customer service is outstanding!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h6 class="author-name">John Smith</h6>
                            <small class="author-title">Professional Contractor</small>
                        </div>
                    </div>
                    <div class="testimonial-hover"></div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Great selection of tools and competitive prices. Highly recommended!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h6 class="author-name">Sarah Johnson</h6>
                            <small class="author-title">DIY Enthusiast</small>
                        </div>
                    </div>
                    <div class="testimonial-hover"></div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Reliable service and quality products. Will definitely shop here again!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h6 class="author-name">Mike Davis</h6>
                            <small class="author-title">Home Renovator</small>
                        </div>
                    </div>
                    <div class="testimonial-hover"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section with Enhanced Design -->
<section class="newsletter-section py-5">
    <div class="container">
        <div class="newsletter-content text-center" data-aos="fade-up">
            <div class="newsletter-badge mb-3">
                <span class="badge bg-success-gradient">Stay Updated</span>
            </div>
            <h2 class="newsletter-title">Stay Updated</h2>
            <p class="newsletter-subtitle">Get the latest deals and product updates delivered to your inbox</p>
            <div class="newsletter-form">
                <div class="row justify-content-center">
                    <div class="col-md-6">
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
</section>

<!-- Enhanced JavaScript with Smooth Animations -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS with enhanced settings
    AOS.init({
        duration: 1000,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100,
        delay: 0
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Enhanced parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-section');
        const floatingCards = document.querySelectorAll('.floating-card');
        
        if (hero) {
            hero.style.transform = `translateY(${scrolled * 0.3}px)`;
        }
        
        // Animate floating cards
        floatingCards.forEach((card, index) => {
            const speed = 0.5 + (index * 0.1);
            card.style.transform = `translateY(${scrolled * speed * 0.1}px) rotate(${scrolled * 0.01}deg)`;
        });
    });

    // Button ripple effect
    document.querySelectorAll('.hero-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Newsletter form enhancement
    const newsletterForm = document.querySelector('.newsletter-input-group');
    const newsletterInput = document.querySelector('.newsletter-input');
    const newsletterBtn = document.querySelector('.newsletter-btn');
    
    if (newsletterBtn) {
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

    // Quick view functionality
    document.querySelectorAll('.quick-view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            // Add quick view modal functionality here
            console.log('Quick view for product:', productId);
        });
    });

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
    document.querySelectorAll('.feature-card, .product-card, .testimonial-card').forEach(el => {
        observer.observe(el);
    });
</script>

<?php include 'includes/footer.php'; ?>