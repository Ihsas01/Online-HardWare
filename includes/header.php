<?php
// Use a more robust path resolution that works from both root and admin directories
$configPath = file_exists('php/config.php') ? 'php/config.php' : '../php/config.php';
require_once $configPath;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php
    // Determine the correct path for CSS and other assets
    $assetPath = file_exists('css/style.css') ? '' : '../';
    ?>
    <link rel="stylesheet" href="<?php echo $assetPath; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo $assetPath; ?>css/components.css">
    <script>
        // Simple check to ensure links are working
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                if (link.href.includes('hired-tools.php')) {
                    console.log('Found hired-tools link:', link.href);
                }
            });
        });
    </script>
</head>
<body>
    <!-- Modern Navigation Header -->
    <nav class="navbar navbar-expand-lg modern-header">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand interactive" href="<?php echo $assetPath; ?>index.php">
                <i class="fas fa-tools"></i>
                <span>I-I Brothers</span>
            </a>
            
            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Navigation Links -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>" href="<?php echo $assetPath; ?>index.php">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'products' ? 'active' : ''; ?>" href="<?php echo $assetPath; ?>products.php">
                            <i class="fas fa-box"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'hired-tools' ? 'active' : ''; ?>" href="<?php echo $assetPath; ?>hired-tools.php">
                            <i class="fas fa-wrench"></i>
                            <span>Hired Tools</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>" href="<?php echo $assetPath; ?>contact.php">
                            <i class="fas fa-envelope"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
                
                <!-- User Actions -->
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <!-- Shopping Cart -->
                        <li class="nav-item">
                            <a class="nav-link interactive" href="<?php echo $assetPath; ?>cart.php">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="d-none d-md-inline">Cart</span>
                                <span class="badge bg-danger rounded-pill ms-1">0</span>
                            </a>
                        </li>
                        
                        <!-- Wishlist -->
                        <li class="nav-item">
                            <a class="nav-link interactive" href="<?php echo $assetPath; ?>wishlist.php">
                                <i class="fas fa-heart"></i>
                                <span class="d-none d-md-inline">Wishlist</span>
                            </a>
                        </li>
                        
                        <!-- User Account Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle interactive" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                                <span class="d-none d-md-inline">Account</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item interactive" href="<?php echo $assetPath; ?>profile.php">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item interactive" href="<?php echo $assetPath; ?>orders.php">
                                        <i class="fas fa-shopping-bag me-2"></i>Orders
                                    </a>
                                </li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item interactive" href="<?php echo $assetPath; ?>admin/dashboard.php">
                                            <i class="fas fa-cog me-2"></i>Admin Dashboard
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item interactive" href="<?php echo $assetPath; ?>logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Login Link -->
                        <li class="nav-item">
                            <a class="nav-link interactive" href="<?php echo $assetPath; ?>login.php">
                                <i class="fas fa-sign-in-alt"></i>
                                <span class="d-none d-md-inline">Login</span>
                            </a>
                        </li>
                        
                        <!-- Register Link -->
                        <li class="nav-item">
                            <a class="nav-link interactive" href="<?php echo $assetPath; ?>register.php">
                                <i class="fas fa-user-plus"></i>
                                <span class="d-none d-md-inline">Register</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- JavaScript for enhanced header interactions -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation class
        const header = document.querySelector('.modern-header');
        if (header) {
            header.classList.add('loading');
            setTimeout(() => {
                header.classList.add('loaded');
            }, 100);
        }
        
        // Scroll effect for header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.modern-header');
            if (header) {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            }
        });
        
        // Add hover effects for interactive elements
        const interactiveElements = document.querySelectorAll('.interactive');
        interactiveElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Enhanced dropdown animations
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('show.bs.dropdown', function() {
                const menu = this.querySelector('.dropdown-menu');
                menu.style.animation = 'dropdownFadeIn 0.3s ease';
            });
        });
        
        // Add ripple effect to brand logo
        const brandLogo = document.querySelector('.navbar-brand');
        if (brandLogo) {
            brandLogo.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(59, 130, 246, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = e.clientX - this.offsetLeft + 'px';
                ripple.style.top = e.clientY - this.offsetTop + 'px';
                ripple.style.width = ripple.style.height = '20px';
                ripple.style.pointerEvents = 'none';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        }
    });
    
    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    </script> 