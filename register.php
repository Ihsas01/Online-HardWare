<?php
require_once 'php/config.php';
$pageTitle = 'Register';
$currentPage = 'register';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $first_name = sanitizeInput($_POST['first_name'] ?? '');
    $last_name = sanitizeInput($_POST['last_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($first_name) || empty($last_name) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } else {
        try {
            $conn = getDBConnection();
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email already registered.";
            } else {
                // Check if username already exists
                $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $error = "Username already taken.";
                } else {
                    // Create new user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, 'customer')");
                    $stmt->execute([$username, $email, $hashedPassword, $first_name, $last_name]);
                    
                    $success = "Registration successful! You can now login.";
                }
            }
        } catch (Exception $e) {
            $error = "An error occurred. Please try again later.";
        }
    }
}

include 'includes/header.php';
?>

<!-- Register Hero Section -->
<section class="register-hero">
    <div class="floating-register-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    <div class="container">
        <div class="register-hero-content">
            <div class="register-hero-badge">
                <i class="fas fa-user-plus me-2"></i>Join Our Community
            </div>
            <h1 class="register-hero-title">Create Your Account</h1>
            <p class="register-hero-subtitle">Join thousands of customers who trust us for their hardware needs.</p>
        </div>
    </div>
</section>

<!-- Register Section -->
<section class="register-section">
    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="register-form-card">
                        <div class="register-form-header">
                            <h2 class="register-form-title">Create Account</h2>
                            <p class="register-form-subtitle">Fill in your details to get started</p>
                        </div>
                        <div class="register-form-body">
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
                            
                            <form method="POST" action="" id="registerForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control has-icon" id="first_name" name="first_name" required>
                                            <i class="fas fa-user form-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control has-icon" id="last_name" name="last_name" required>
                                            <i class="fas fa-user form-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control has-icon" id="username" name="username" required>
                                    <i class="fas fa-at form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control has-icon" id="email" name="email" required>
                                    <i class="fas fa-envelope form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control has-icon" id="password" name="password" required>
                                    <i class="fas fa-lock form-icon"></i>
                                    <div class="form-text">Password must be at least 8 characters long.</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control has-icon" id="confirm_password" name="confirm_password" required>
                                    <i class="fas fa-lock form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="privacy-link">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </form>
                            
                            <div class="register-actions">
                                <div class="text-center">
                                    <p class="mb-0">Already have an account? <a href="login.php" class="login-link">Sign in here</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h5>Fast Delivery</h5>
                    <p class="text-muted">Get your tools delivered quickly and safely to your doorstep.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-tools fa-3x text-primary"></i>
                    </div>
                    <h5>Tool Rental</h5>
                    <p class="text-muted">Rent professional tools for your projects without buying them.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5>24/7 Support</h5>
                    <p class="text-muted">Our customer support team is always here to help you.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Register CSS -->
<link rel="stylesheet" href="css/register.css">

<!-- Register Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = form.querySelector('.submit-btn');
    
    // Form submission animation
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
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
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        const icon = field.nextElementSibling;
        icon.addEventListener('click', function() {
            if (field.type === 'password') {
                field.type = 'text';
                this.classList.remove('fa-lock');
                this.classList.add('fa-eye');
            } else {
                field.type = 'password';
                this.classList.remove('fa-eye');
                this.classList.add('fa-lock');
            }
        });
    });
    
    // Password strength indicator
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    
    function checkPasswordMatch() {
        if (confirmPasswordField.value && passwordField.value !== confirmPasswordField.value) {
            confirmPasswordField.style.borderColor = '#ef4444';
        } else {
            confirmPasswordField.style.borderColor = '#e5e7eb';
        }
    }
    
    passwordField.addEventListener('input', checkPasswordMatch);
    confirmPasswordField.addEventListener('input', checkPasswordMatch);
});
</script>

<?php include 'includes/footer.php'; ?> 