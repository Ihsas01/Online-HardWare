<?php
require_once 'php/config.php';
$pageTitle = 'Contact Us';
$currentPage = 'contact';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            
            if (sendEmail(ADMIN_EMAIL, "New Contact Form Submission: $subject", 
                "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message")) {
                $success = "Thank you for your message. We'll get back to you soon!";
            } else {
                $error = "Message saved but could not send email notification.";
            }
        } catch (Exception $e) {
            $error = "An error occurred. Please try again later.";
        }
    }
}

include 'includes/header.php';
?>

<!-- Contact Hero Section -->
<section class="contact-hero">
    <div class="floating-contact-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    <div class="container">
        <div class="contact-hero-content">
            <div class="contact-hero-badge">
                <i class="fas fa-envelope me-2"></i>Get in Touch
            </div>
            <h1 class="contact-hero-title">Let's Start a Conversation</h1>
            <p class="contact-hero-subtitle">We're here to help and answer any questions you might have. We look forward to hearing from you.</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="contact-container">
        <div class="container">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="contact-form-card">
                        <div class="contact-form-header">
                            <h2 class="contact-form-title">Send us a Message</h2>
                            <p class="contact-form-subtitle">We'll get back to you within 24 hours</p>
                        </div>
                        <div class="contact-form-body">
                            <form method="POST" action="" id="contactForm">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control has-icon" id="name" name="name" required>
                                    <i class="fas fa-user form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control has-icon" id="email" name="email" required>
                                    <i class="fas fa-envelope form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control has-icon" id="subject" name="subject" required>
                                    <i class="fas fa-tag form-icon"></i>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message" class="form-label">Your Message</label>
                                    <textarea class="form-control has-icon" id="message" name="message" rows="5" required placeholder="Tell us how we can help you..."></textarea>
                                    <i class="fas fa-comment form-icon"></i>
                                </div>
                                
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="col-lg-6">
                    <div class="contact-info-card mb-4">
                        <div class="contact-info-header">
                            <h2 class="contact-info-title">Contact Information</h2>
                            <p class="contact-info-subtitle">Reach out to us through any of these channels</p>
                        </div>
                        <div class="contact-info-body">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Our Location</h6>
                                    <p>123 Main Street, Colombo, Sri Lanka</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Phone Number</h6>
                                    <p>076 xxx xxxx<br>Mon-Sat: 8:00 AM - 8:00 PM</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Email Address</h6>
                                    <p>mohamedihsas001@gmail.com<br>We respond within 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Business Hours</h6>
                                    <p>Monday - Saturday: 8:00 AM - 8:00 PM<br>Sunday: 10:00 AM - 6:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map Section -->
                    <div class="map-card">
                        <div class="map-header">
                            <h2 class="map-title">Find Us</h2>
                            <p class="map-subtitle">Visit our store location</p>
                        </div>
                        <div class="map-container">
                            <iframe class="map-iframe" 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30591910525!2d-74.25986432970718!3d40.697149422113014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1645564757461!5m2!1sen!2s" 
                                    allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5>24/7 Support</h5>
                    <p class="text-muted">Our customer support team is available around the clock to assist you with any questions or concerns.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h5>Fast Delivery</h5>
                    <p class="text-muted">Quick and reliable delivery service to get your hardware tools to you as soon as possible.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5>Secure Payments</h5>
                    <p class="text-muted">Your payment information is protected with the highest level of security and encryption.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Contact CSS -->
<link rel="stylesheet" href="css/contact.css">

<!-- Contact Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = form.querySelector('.submit-btn');
    
    // Form submission animation
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Message';
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
    
    // Success animation
    <?php if ($success): ?>
    setTimeout(() => {
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            successAlert.classList.add('form-success');
        }
    }, 100);
    <?php endif; ?>
});

// Smooth scroll for anchor links
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
</script>

<?php include 'includes/footer.php'; ?> 