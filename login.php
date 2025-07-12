<?php
require_once 'php/config.php';
$pageTitle = 'Login';
$currentPage = 'login';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $conn = getDBConnection();
            
            // Check if user exists
            $stmt = $conn->prepare("SELECT id, username, email, password, first_name, last_name, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];
                
                // Redirect to appropriate page
                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('index.php');
                }
            } else {
                $error = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $error = "An error occurred. Please try again later.";
        }
    }
}

include 'includes/header.php';
?>

<!-- Login Hero Section -->
<section class="login-hero">
    <div class="floating-login-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    <div class="container">
        <div class="login-hero-content">
            <div class="login-hero-badge">
                <i class="fas fa-sign-in-alt me-2"></i>Welcome Back
            </div>
            <h1 class="login-hero-title">Sign In to Your Account</h1>
            <p class="login-hero-subtitle">Access your account to manage orders, track deliveries, and more.</p>
        </div>
    </div>
</section>

<!-- Login Section -->
<section class="login-section">
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="login-form-card">
                        <div class="login-form-header">
                            <h2 class="login-form-title">Sign In</h2>
                            <p class="login-form-subtitle">Enter your credentials to access your account</p>
                        </div>
                        <div class="login-form-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="" id="loginForm">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control has-icon" id="email" name="email" required>
                                    <i class="fas fa-envelope form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control has-icon" id="password" name="password" required>
                                    <i class="fas fa-lock form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </form>
                            
                            <div class="login-actions">
                                <div class="text-center">
                                    <a href="forgot-password.php" class="forgot-link">Forgot your password?</a>
                                </div>
                                <div class="text-center mt-3">
                                    <p class="mb-0">Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                    </div>
                    <h5>Track Orders</h5>
                    <p class="text-muted">Monitor your order status and track deliveries in real-time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-tools fa-3x text-primary"></i>
                    </div>
                    <h5>Hire Tools</h5>
                    <p class="text-muted">Rent professional tools and equipment for your projects.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-heart fa-3x text-primary"></i>
                    </div>
                    <h5>Wishlist</h5>
                    <p class="text-muted">Save your favorite products for future purchases.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Login CSS -->
<link rel="stylesheet" href="css/login.css">

<!-- Login Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitBtn = form.querySelector('.submit-btn');
    
    // Form submission animation
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Form field focus effects
    const formControls = form.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        control.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    
    // Password visibility toggle
    const passwordField = document.getElementById('password');
    const passwordIcon = passwordField.nextElementSibling;
    
    passwordIcon.addEventListener('click', function() {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.classList.remove('fa-lock');
            this.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            this.classList.remove('fa-eye');
            this.classList.add('fa-lock');
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?> 