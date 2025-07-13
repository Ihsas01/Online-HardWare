<?php
require_once 'php/config.php';
$pageTitle = 'Terms & Conditions';
$currentPage = 'terms';

include 'includes/header.php';
?>

<!-- Terms & Conditions Hero Section -->
<section class="terms-hero">
    <div class="terms-floating-elements">
        <div class="terms-floating-element"></div>
        <div class="terms-floating-element"></div>
        <div class="terms-floating-element"></div>
    </div>
    
    <div class="container">
        <div class="terms-hero-content">
            <div class="terms-hero-badge">
                <i class="fas fa-gavel me-2"></i>
                Legal Terms
            </div>
            
            <h1 class="terms-hero-title">Terms & Conditions</h1>
            <p class="terms-hero-subtitle">
                Please read these terms and conditions carefully before using our website and services. 
                These terms govern your use of I-I Brothers Hardware Store.
            </p>
            
            <div class="terms-hero-meta">
                <div class="terms-meta-item">
                    <div class="terms-meta-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span>Last updated: <?php echo date('F d, Y'); ?></span>
                </div>
                <div class="terms-meta-item">
                    <div class="terms-meta-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span>Reading time: ~8 minutes</span>
                </div>
                <div class="terms-meta-item">
                    <div class="terms-meta-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span>Legal Protection</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Terms & Conditions Content Section -->
<section class="terms-content">
    <div class="container">
        <div class="terms-container">
            <div class="terms-card">
                <div class="terms-card-header">
                    <h2 class="terms-card-title">
                        <i class="fas fa-file-contract"></i>
                        Terms & Conditions
                    </h2>
                    <div class="terms-card-meta">
                        <span><i class="fas fa-calendar me-1"></i> Effective: <?php echo date('F d, Y'); ?></span>
                        <span><i class="fas fa-building me-1"></i> I-I Brothers Hardware Store</span>
                        <span><i class="fas fa-globe me-1"></i> Legal Agreement</span>
                    </div>
                </div>
                
                <div class="terms-card-body">
                    <!-- Acceptance of Terms Section -->
                    <div class="terms-section important">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">1</span>
                            <i class="fas fa-check-circle"></i>
                            Acceptance of Terms
                        </h3>
                        <div class="terms-section-content">
                            <p>By accessing and using the I-I Brothers Hardware Store website and services, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                        </div>
                    </div>
                    
                    <!-- Use License Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">2</span>
                            <i class="fas fa-key"></i>
                            Use License
                        </h3>
                        <div class="terms-section-content">
                            <p>Permission is granted to temporarily access the materials (information or software) on I-I Brothers Hardware Store's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                            <ul class="terms-list">
                                <li class="terms-list-item">Modify or copy the materials</li>
                                <li class="terms-list-item">Use the materials for any commercial purpose or for any public display</li>
                                <li class="terms-list-item">Attempt to reverse engineer any software contained on the website</li>
                                <li class="terms-list-item">Remove any copyright or other proprietary notations from the materials</li>
                                <li class="terms-list-item">Transfer the materials to another person or "mirror" the materials on any other server</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Account Registration Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">3</span>
                            <i class="fas fa-user-plus"></i>
                            Account Registration
                        </h3>
                        <div class="terms-section-content">
                            <p>To access certain features of our website, you must register for an account. You agree to:</p>
                            <ul class="terms-list">
                                <li class="terms-list-item">Provide accurate, current, and complete information during registration</li>
                                <li class="terms-list-item">Maintain and promptly update your account information</li>
                                <li class="terms-list-item">Maintain the security of your password and account</li>
                                <li class="terms-list-item">Accept responsibility for all activities that occur under your account</li>
                                <li class="terms-list-item">Notify us immediately of any unauthorized use of your account</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Product Sales Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">4</span>
                            <i class="fas fa-shopping-cart"></i>
                            Product Sales
                        </h3>
                        <div class="terms-section-content">
                            <div class="terms-highlight-box">
                                <h4><i class="fas fa-credit-card"></i> Pricing and Payment</h4>
                                <ul class="terms-list">
                                    <li class="terms-list-item">All prices are subject to change without notice</li>
                                    <li class="terms-list-item">Payment must be made in full at the time of purchase</li>
                                    <li class="terms-list-item">We accept major credit cards and other payment methods as indicated</li>
                                    <li class="terms-list-item">Sales tax will be added where applicable</li>
                                </ul>
                            </div>
                            
                            <div class="terms-highlight-box">
                                <h4><i class="fas fa-truck"></i> Shipping and Delivery</h4>
                                <ul class="terms-list">
                                    <li class="terms-list-item">Delivery times are estimates and may vary</li>
                                    <li class="terms-list-item">Risk of loss and title for items pass to you upon delivery</li>
                                    <li class="terms-list-item">You are responsible for providing accurate shipping information</li>
                                </ul>
                            </div>
                            
                            <div class="terms-highlight-box">
                                <h4><i class="fas fa-undo"></i> Returns and Refunds</h4>
                                <ul class="terms-list">
                                    <li class="terms-list-item">Returns must be made within 30 days of purchase</li>
                                    <li class="terms-list-item">Items must be in original condition and packaging</li>
                                    <li class="terms-list-item">Shipping costs for returns are the responsibility of the customer</li>
                                    <li class="terms-list-item">Refunds will be processed within 5-7 business days</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tool Rental Services Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">5</span>
                            <i class="fas fa-tools"></i>
                            Tool Rental Services
                        </h3>
                        <div class="terms-section-content">
                            <div class="terms-highlight-box">
                                <h4><i class="fas fa-calendar-check"></i> Rental Terms</h4>
                                <ul class="terms-list">
                                    <li class="terms-list-item">Rental periods are calculated from pickup to return</li>
                                    <li class="terms-list-item">Valid identification and credit card are required for rental</li>
                                    <li class="terms-list-item">Rental rates are charged per day or as specified</li>
                                    <li class="terms-list-item">Late returns will incur additional charges</li>
                                </ul>
                            </div>
                            
                            <div class="terms-highlight-box">
                                <h4><i class="fas fa-user-shield"></i> Rental Responsibilities</h4>
                                <ul class="terms-list">
                                    <li class="terms-list-item">You are responsible for the care and maintenance of rented equipment</li>
                                    <li class="terms-list-item">Equipment must be returned in the same condition as received</li>
                                    <li class="terms-list-item">Damage or loss of equipment will result in replacement charges</li>
                                    <li class="terms-list-item">Safety instructions must be followed at all times</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prohibited Uses Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">6</span>
                            <i class="fas fa-ban"></i>
                            Prohibited Uses
                        </h3>
                        <div class="terms-section-content">
                            <p>You may not use our website or services to:</p>
                            <ul class="terms-list">
                                <li class="terms-list-item">Violate any applicable laws or regulations</li>
                                <li class="terms-list-item">Infringe upon the rights of others</li>
                                <li class="terms-list-item">Transmit harmful, offensive, or inappropriate content</li>
                                <li class="terms-list-item">Attempt to gain unauthorized access to our systems</li>
                                <li class="terms-list-item">Interfere with the proper functioning of our website</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Privacy Policy Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">7</span>
                            <i class="fas fa-shield-alt"></i>
                            Privacy Policy
                        </h3>
                        <div class="terms-section-content">
                            <p>Your privacy is important to us. Please review our <a href="privacy.php" class="terms-link">Privacy Policy</a>, which also governs your use of the website, to understand our practices.</p>
                        </div>
                    </div>
                    
                    <!-- Intellectual Property Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">8</span>
                            <i class="fas fa-copyright"></i>
                            Intellectual Property
                        </h3>
                        <div class="terms-section-content">
                            <p>The content on this website, including text, graphics, logos, images, and software, is the property of I-I Brothers Hardware Store and is protected by copyright laws. You may not reproduce, distribute, or create derivative works without our express written consent.</p>
                        </div>
                    </div>
                    
                    <!-- Disclaimer Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">9</span>
                            <i class="fas fa-exclamation-triangle"></i>
                            Disclaimer
                        </h3>
                        <div class="terms-section-content">
                            <p>The materials on I-I Brothers Hardware Store's website are provided on an 'as is' basis. I-I Brothers Hardware Store makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                        </div>
                    </div>
                    
                    <!-- Limitations Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">10</span>
                            <i class="fas fa-gavel"></i>
                            Limitations
                        </h3>
                        <div class="terms-section-content">
                            <p>In no event shall I-I Brothers Hardware Store or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on the website, even if I-I Brothers Hardware Store or an authorized representative has been notified orally or in writing of the possibility of such damage.</p>
                        </div>
                    </div>
                    
                    <!-- Revisions and Errata Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">11</span>
                            <i class="fas fa-edit"></i>
                            Revisions and Errata
                        </h3>
                        <div class="terms-section-content">
                            <p>The materials appearing on I-I Brothers Hardware Store's website could include technical, typographical, or photographic errors. I-I Brothers Hardware Store does not warrant that any of the materials on its website are accurate, complete, or current. I-I Brothers Hardware Store may make changes to the materials contained on its website at any time without notice.</p>
                        </div>
                    </div>
                    
                    <!-- Links Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">12</span>
                            <i class="fas fa-link"></i>
                            Links
                        </h3>
                        <div class="terms-section-content">
                            <p>I-I Brothers Hardware Store has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by I-I Brothers Hardware Store of the site. Use of any such linked website is at the user's own risk.</p>
                        </div>
                    </div>
                    
                    <!-- Modifications Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">13</span>
                            <i class="fas fa-cogs"></i>
                            Modifications
                        </h3>
                        <div class="terms-section-content">
                            <p>I-I Brothers Hardware Store may revise these terms of service for its website at any time without notice. By using this website, you are agreeing to be bound by the then current version of these Terms of Service.</p>
                        </div>
                    </div>
                    
                    <!-- Governing Law Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">14</span>
                            <i class="fas fa-balance-scale"></i>
                            Governing Law
                        </h3>
                        <div class="terms-section-content">
                            <p>These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which I-I Brothers Hardware Store operates, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
                        </div>
                    </div>
                    
                    <!-- Contact Information Section -->
                    <div class="terms-section">
                        <h3 class="terms-section-title">
                            <span class="terms-section-number">15</span>
                            <i class="fas fa-phone"></i>
                            Contact Information
                        </h3>
                        <div class="terms-section-content">
                            <p>If you have any questions about these Terms & Conditions, please contact us:</p>
                            
                            <div class="terms-contact-info">
                                <div class="terms-contact-item">
                                    <div class="terms-contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="terms-contact-details">
                                        <div class="terms-contact-label">Email Address</div>
                                        <div class="terms-contact-value"><?php echo ADMIN_EMAIL; ?></div>
                                    </div>
                                </div>
                                
                                <div class="terms-contact-item">
                                    <div class="terms-contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="terms-contact-details">
                                        <div class="terms-contact-label">Phone Number</div>
                                        <div class="terms-contact-value">(555) 123-4567</div>
                                    </div>
                                </div>
                                
                                <div class="terms-contact-item">
                                    <div class="terms-contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="terms-contact-details">
                                        <div class="terms-contact-label">Business Address</div>
                                        <div class="terms-contact-value">123 Hardware St, City, State</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Final Alert -->
                    <div class="terms-alert">
                        <div class="terms-alert-header">
                            <i class="fas fa-info-circle terms-alert-icon"></i>
                            <h4 class="terms-alert-title">Important Notice</h4>
                        </div>
                        <div class="terms-alert-content">
                            <strong>Note:</strong> These terms and conditions apply to all users of the I-I Brothers Hardware Store website and services. By using our website, you acknowledge that you have read, understood, and agree to be bound by these terms.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add the terms.css file -->
<link rel="stylesheet" href="css/terms.css">

<!-- JavaScript for enhanced interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation class
    const termsCard = document.querySelector('.terms-card');
    if (termsCard) {
        termsCard.classList.add('terms-loading');
        setTimeout(() => {
            termsCard.classList.add('loaded');
        }, 100);
    }
    
    // Add smooth scroll for section navigation
    const sections = document.querySelectorAll('.terms-section');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    sections.forEach(section => {
        observer.observe(section);
    });
    
    // Add hover effects for interactive elements
    const listItems = document.querySelectorAll('.terms-list-item');
    listItems.forEach(item => {
        item.classList.add('terms-interactive');
    });
    
    // Add click effects for contact items
    const contactItems = document.querySelectorAll('.terms-contact-item');
    contactItems.forEach(item => {
        item.classList.add('terms-interactive');
    });
    
    // Add click effects for highlight boxes
    const highlightBoxes = document.querySelectorAll('.terms-highlight-box');
    highlightBoxes.forEach(box => {
        box.classList.add('terms-interactive');
    });
});
</script>

<?php include 'includes/footer.php'; ?> 