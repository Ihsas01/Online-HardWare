<?php
require_once 'php/config.php';
$pageTitle = 'My Profile';
$currentPage = 'profile';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';
$success = '';

try {
    $conn = getDBConnection();
    
    // Get user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        redirect('logout.php');
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = sanitizeInput($_POST['first_name'] ?? '');
        $last_name = sanitizeInput($_POST['last_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = "First name, last name, and email are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            // Check if email is already taken by another user
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user['id']]);
            if ($stmt->fetch()) {
                $error = "Email is already taken.";
            } else {
                // Update basic info
                $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $email, $user['id']]);
                
                // Update session
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $_SESSION['email'] = $email;
                
                // Handle password change if requested
                if (!empty($currentPassword)) {
                    if (empty($newPassword) || empty($confirmPassword)) {
                        $error = "New password and confirmation are required.";
                    } elseif (strlen($newPassword) < 8) {
                        $error = "New password must be at least 8 characters long.";
                    } elseif ($newPassword !== $confirmPassword) {
                        $error = "New passwords do not match.";
                    } elseif (!password_verify($currentPassword, $user['password'])) {
                        $error = "Current password is incorrect.";
                    } else {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$hashedPassword, $user['id']]);
                        $success = "Profile and password updated successfully.";
                    }
                } else {
                    $success = "Profile updated successfully.";
                }
                
                // Refresh user data
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user['id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
} catch (Exception $e) {
    $error = "An error occurred. Please try again later.";
}

include 'includes/header.php';
?>

<!-- Profile Hero Section -->
<section class="profile-hero">
    <div class="floating-profile-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    <div class="container">
        <div class="profile-hero-content">
            <div class="profile-hero-badge">
                <i class="fas fa-user-circle me-2"></i>My Account
            </div>
            <h1 class="profile-hero-title">Manage Your Profile</h1>
            <p class="profile-hero-subtitle">Update your personal information and account settings.</p>
        </div>
    </div>
</section>

<!-- Profile Section -->
<section class="profile-section">
    <div class="profile-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="profile-form-card">
                        <div class="profile-form-header">
                            <h2 class="profile-form-title">My Profile</h2>
                            <p class="profile-form-subtitle">Update your personal information</p>
                        </div>
                        <div class="profile-form-body">
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
                            
                            <form method="POST" action="" id="profileForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control has-icon" id="first_name" name="first_name" 
                                                   value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                                            <i class="fas fa-user form-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control has-icon" id="last_name" name="last_name" 
                                                   value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                                            <i class="fas fa-user form-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control has-icon" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                    <i class="fas fa-envelope form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control has-icon" id="username" name="username" 
                                           value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" readonly>
                                    <i class="fas fa-at form-icon"></i>
                                    <div class="form-text">Username cannot be changed</div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="password-section">
                                    <h5 class="password-section-title">
                                        <i class="fas fa-lock me-2"></i>Change Password
                                    </h5>
                                    <p class="password-section-subtitle">Leave blank if you don't want to change your password</p>
                                    
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control has-icon" id="current_password" name="current_password">
                                        <i class="fas fa-lock form-icon"></i>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control has-icon" id="new_password" name="new_password">
                                        <i class="fas fa-lock form-icon"></i>
                                        <div class="form-text">Password must be at least 8 characters long.</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control has-icon" id="confirm_password" name="confirm_password">
                                        <i class="fas fa-lock form-icon"></i>
                                    </div>
                                </div>
                                
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Account Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shopping-bag fa-3x text-primary"></i>
                    </div>
                    <h5>My Orders</h5>
                    <p class="text-muted">Track your order history and current purchases.</p>
                    <a href="orders.php" class="btn btn-outline-primary">View Orders</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-tools fa-3x text-primary"></i>
                    </div>
                    <h5>Hired Tools</h5>
                    <p class="text-muted">Manage your tool rentals and return dates.</p>
                    <a href="hired-tools.php" class="btn btn-outline-primary">View Hired Tools</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-heart fa-3x text-primary"></i>
                    </div>
                    <h5>Wishlist</h5>
                    <p class="text-muted">View your saved items and favorites.</p>
                    <a href="wishlist.php" class="btn btn-outline-primary">View Wishlist</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Profile CSS -->
<link rel="stylesheet" href="css/profile.css">

<!-- Profile Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const submitBtn = form.querySelector('.submit-btn');
    
    // Form submission animation
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Profile';
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
    
    // Password match validation
    const newPasswordField = document.getElementById('new_password');
    const confirmPasswordField = document.getElementById('confirm_password');
    
    function checkPasswordMatch() {
        if (confirmPasswordField.value && newPasswordField.value !== confirmPasswordField.value) {
            confirmPasswordField.classList.add('password-mismatch');
            confirmPasswordField.classList.remove('password-match');
        } else if (confirmPasswordField.value && newPasswordField.value === confirmPasswordField.value) {
            confirmPasswordField.classList.remove('password-mismatch');
            confirmPasswordField.classList.add('password-match');
        } else {
            confirmPasswordField.classList.remove('password-mismatch', 'password-match');
        }
    }
    
    newPasswordField.addEventListener('input', checkPasswordMatch);
    confirmPasswordField.addEventListener('input', checkPasswordMatch);
});
</script>

<?php include 'includes/footer.php'; ?> 