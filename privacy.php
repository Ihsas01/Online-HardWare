<?php
require_once 'php/config.php';
$pageTitle = 'Privacy Policy';
$currentPage = 'privacy';

include 'includes/header.php';
?>

<!-- Privacy Policy Hero Section -->
<section class="privacy-hero">
    <div class="privacy-floating-elements">
        <div class="privacy-floating-element"></div>
        <div class="privacy-floating-element"></div>
        <div class="privacy-floating-element"></div>
    </div>
    
    <div class="container">
        <div class="privacy-hero-content">
            <div class="privacy-hero-badge">
                <i class="fas fa-shield-alt me-2"></i>
                Privacy & Security
            </div>
            
            <h1 class="privacy-hero-title">Privacy Policy</h1>
            <p class="privacy-hero-subtitle">
                We are committed to protecting your privacy and ensuring the security of your personal information. 
                This policy outlines how we collect, use, and safeguard your data.
            </p>
            
            <div class="privacy-hero-meta">
                <div class="privacy-meta-item">
                    <div class="privacy-meta-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span>Last updated: <?php echo date('F d, Y'); ?></span>
                </div>
                <div class="privacy-meta-item">
                    <div class="privacy-meta-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span>Reading time: ~5 minutes</span>
                </div>
                <div class="privacy-meta-item">
                    <div class="privacy-meta-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <span>Transparent & Clear</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Policy Content Section -->
<section class="privacy-content">
    <div class="container">
        <div class="privacy-container">
            <div class="privacy-card">
                <div class="privacy-card-header">
                    <h2 class="privacy-card-title">
                        <i class="fas fa-file-contract"></i>
                        Privacy Policy
                    </h2>
                    <div class="privacy-card-meta">
                        <span><i class="fas fa-calendar me-1"></i> Effective: <?php echo date('F d, Y'); ?></span>
                        <span><i class="fas fa-building me-1"></i> I-I Brothers Hardware Store</span>
                        <span><i class="fas fa-globe me-1"></i> Online Privacy</span>
                    </div>
                </div>
                
                <div class="privacy-card-body">
                    <!-- Information Collection Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-database"></i>
                            1. Information We Collect
                        </h3>
                        <div class="privacy-section-content">
                            <p>We collect information you provide directly to us, such as when you:</p>
                            <ul class="privacy-list">
                                <li class="privacy-list-item">Create an account or register on our website</li>
                                <li class="privacy-list-item">Place orders for products or hire tools</li>
                                <li class="privacy-list-item">Contact us for support or inquiries</li>
                                <li class="privacy-list-item">Subscribe to our newsletter</li>
                                <li class="privacy-list-item">Participate in surveys or promotions</li>
                            </ul>
                            
                            <div class="privacy-highlight-box">
                                <h4><i class="fas fa-user-shield"></i> Personal Information</h4>
                                <p>This may include:</p>
                                <ul class="privacy-list">
                                    <li class="privacy-list-item">Name, email address, and contact information</li>
                                    <li class="privacy-list-item">Billing and shipping addresses</li>
                                    <li class="privacy-list-item">Payment information (processed securely)</li>
                                    <li class="privacy-list-item">Account credentials and preferences</li>
                                    <li class="privacy-list-item">Communication history with our support team</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Information Usage Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-cogs"></i>
                            2. How We Use Your Information
                        </h3>
                        <div class="privacy-section-content">
                            <p>We use the information we collect to:</p>
                            <ul class="privacy-list">
                                <li class="privacy-list-item">Process and fulfill your orders</li>
                                <li class="privacy-list-item">Provide customer support and respond to inquiries</li>
                                <li class="privacy-list-item">Send order confirmations and updates</li>
                                <li class="privacy-list-item">Manage your account and preferences</li>
                                <li class="privacy-list-item">Improve our website and services</li>
                                <li class="privacy-list-item">Send marketing communications (with your consent)</li>
                                <li class="privacy-list-item">Comply with legal obligations</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Information Sharing Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-share-alt"></i>
                            3. Information Sharing
                        </h3>
                        <div class="privacy-section-content">
                            <p>We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:</p>
                            <ul class="privacy-list">
                                <li class="privacy-list-item">
                                    <strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our website, processing payments, and delivering services.
                                </li>
                                <li class="privacy-list-item">
                                    <strong>Legal Requirements:</strong> We may disclose information when required by law or to protect our rights and safety.
                                </li>
                                <li class="privacy-list-item">
                                    <strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction.
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Data Security Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-lock"></i>
                            4. Data Security
                        </h3>
                        <div class="privacy-section-content">
                            <p>We implement appropriate security measures to protect your personal information:</p>
                            <ul class="privacy-list">
                                <li class="privacy-list-item">Encryption of sensitive data during transmission</li>
                                <li class="privacy-list-item">Secure storage of personal information</li>
                                <li class="privacy-list-item">Regular security assessments and updates</li>
                                <li class="privacy-list-item">Access controls and authentication measures</li>
                                <li class="privacy-list-item">Employee training on data protection</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Cookies and Tracking Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-cookie-bite"></i>
                            5. Cookies and Tracking
                        </h3>
                        <div class="privacy-section-content">
                            <p>We use cookies and similar technologies to:</p>
                            <ul class="privacy-list">
                                <li class="privacy-list-item">Remember your preferences and settings</li>
                                <li class="privacy-list-item">Analyze website traffic and usage patterns</li>
                                <li class="privacy-list-item">Improve website functionality and user experience</li>
                                <li class="privacy-list-item">Provide personalized content and recommendations</li>
                            </ul>
                            <p>You can control cookie settings through your browser preferences.</p>
                        </div>
                    </div>
                    
                    <!-- Your Rights Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-user-check"></i>
                            6. Your Rights and Choices
                        </h3>
                        <div class="privacy-section-content">
                            <p>You have the right to:</p>
                            <ul class="privacy-list">
                                <li class="privacy-list-item">Access and review your personal information</li>
                                <li class="privacy-list-item">Update or correct inaccurate information</li>
                                <li class="privacy-list-item">Request deletion of your personal information</li>
                                <li class="privacy-list-item">Opt-out of marketing communications</li>
                                <li class="privacy-list-item">Withdraw consent for data processing</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Children's Privacy Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-child"></i>
                            7. Children's Privacy
                        </h3>
                        <div class="privacy-section-content">
                            <p>Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately.</p>
                        </div>
                    </div>
                    
                    <!-- International Data Transfers Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-globe-americas"></i>
                            8. International Data Transfers
                        </h3>
                        <div class="privacy-section-content">
                            <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.</p>
                        </div>
                    </div>
                    
                    <!-- Policy Changes Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-edit"></i>
                            9. Changes to This Policy
                        </h3>
                        <div class="privacy-section-content">
                            <p>We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on our website and updating the "Last updated" date.</p>
                        </div>
                    </div>
                    
                    <!-- Contact Information Section -->
                    <div class="privacy-section">
                        <h3 class="privacy-section-title">
                            <i class="fas fa-phone"></i>
                            10. Contact Us
                        </h3>
                        <div class="privacy-section-content">
                            <p>If you have any questions about this privacy policy or our data practices, please contact us:</p>
                            
                            <div class="privacy-contact-info">
                                <div class="privacy-contact-item">
                                    <div class="privacy-contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="privacy-contact-details">
                                        <div class="privacy-contact-label">Email Address</div>
                                        <div class="privacy-contact-value">mohamedihsas001@gmail.com</div>
                                    </div>
                                </div>
                                
                                <div class="privacy-contact-item">
                                    <div class="privacy-contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="privacy-contact-details">
                                        <div class="privacy-contact-label">Phone Number</div>
                                        <div class="privacy-contact-value">076 xxx xxxx</div>
                                    </div>
                                </div>
                                
                                <div class="privacy-contact-item">
                                    <div class="privacy-contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="privacy-contact-details">
                                        <div class="privacy-contact-label">Business Address</div>
                                        <div class="privacy-contact-value">123 Main Street, Colombo, Sri Lanka</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Final Alert -->
                    <div class="privacy-alert">
                        <div class="privacy-alert-header">
                            <i class="fas fa-info-circle privacy-alert-icon"></i>
                            <h4 class="privacy-alert-title">Important Notice</h4>
                        </div>
                        <div class="privacy-alert-content">
                            <strong>Note:</strong> This privacy policy applies to the I-I Brothers Hardware Store website and services. By using our website, you consent to the collection and use of information as described in this policy.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add the privacy.css file -->
<link rel="stylesheet" href="css/privacy.css">

<!-- JavaScript for enhanced interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation class
    const privacyCard = document.querySelector('.privacy-card');
    if (privacyCard) {
        privacyCard.classList.add('privacy-loading');
        setTimeout(() => {
            privacyCard.classList.add('loaded');
        }, 100);
    }
    
    // Add smooth scroll for section navigation
    const sections = document.querySelectorAll('.privacy-section');
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
    const listItems = document.querySelectorAll('.privacy-list-item');
    listItems.forEach(item => {
        item.classList.add('privacy-interactive');
    });
    
    // Add click effects for contact items
    const contactItems = document.querySelectorAll('.privacy-contact-item');
    contactItems.forEach(item => {
        item.classList.add('privacy-interactive');
    });
});
</script>

<?php include 'includes/footer.php'; ?> 