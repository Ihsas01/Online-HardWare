<?php
require_once 'php/config.php';
$pageTitle = 'Privacy Policy';
$currentPage = 'privacy';

include 'includes/header.php';
?>

<!-- Privacy Policy Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="mb-4">Privacy Policy</h1>
                <p class="text-muted mb-4">Last updated: <?php echo date('F d, Y'); ?></p>
                
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">1. Information We Collect</h2>
                        <p>We collect information you provide directly to us, such as when you:</p>
                        <ul>
                            <li>Create an account or register on our website</li>
                            <li>Place orders for products or hire tools</li>
                            <li>Contact us for support or inquiries</li>
                            <li>Subscribe to our newsletter</li>
                            <li>Participate in surveys or promotions</li>
                        </ul>
                        
                        <h3 class="h5 mt-4 mb-3">Personal Information</h3>
                        <p>This may include:</p>
                        <ul>
                            <li>Name, email address, and contact information</li>
                            <li>Billing and shipping addresses</li>
                            <li>Payment information (processed securely)</li>
                            <li>Account credentials and preferences</li>
                            <li>Communication history with our support team</li>
                        </ul>
                        
                        <h2 class="h4 mb-3 mt-5">2. How We Use Your Information</h2>
                        <p>We use the information we collect to:</p>
                        <ul>
                            <li>Process and fulfill your orders</li>
                            <li>Provide customer support and respond to inquiries</li>
                            <li>Send order confirmations and updates</li>
                            <li>Manage your account and preferences</li>
                            <li>Improve our website and services</li>
                            <li>Send marketing communications (with your consent)</li>
                            <li>Comply with legal obligations</li>
                        </ul>
                        
                        <h2 class="h4 mb-3 mt-5">3. Information Sharing</h2>
                        <p>We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:</p>
                        <ul>
                            <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our website, processing payments, and delivering services.</li>
                            <li><strong>Legal Requirements:</strong> We may disclose information when required by law or to protect our rights and safety.</li>
                            <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction.</li>
                        </ul>
                        
                        <h2 class="h4 mb-3 mt-5">4. Data Security</h2>
                        <p>We implement appropriate security measures to protect your personal information:</p>
                        <ul>
                            <li>Encryption of sensitive data during transmission</li>
                            <li>Secure storage of personal information</li>
                            <li>Regular security assessments and updates</li>
                            <li>Access controls and authentication measures</li>
                            <li>Employee training on data protection</li>
                        </ul>
                        
                        <h2 class="h4 mb-3 mt-5">5. Cookies and Tracking</h2>
                        <p>We use cookies and similar technologies to:</p>
                        <ul>
                            <li>Remember your preferences and settings</li>
                            <li>Analyze website traffic and usage patterns</li>
                            <li>Improve website functionality and user experience</li>
                            <li>Provide personalized content and recommendations</li>
                        </ul>
                        <p>You can control cookie settings through your browser preferences.</p>
                        
                        <h2 class="h4 mb-3 mt-5">6. Your Rights and Choices</h2>
                        <p>You have the right to:</p>
                        <ul>
                            <li>Access and review your personal information</li>
                            <li>Update or correct inaccurate information</li>
                            <li>Request deletion of your personal information</li>
                            <li>Opt-out of marketing communications</li>
                            <li>Withdraw consent for data processing</li>
                        </ul>
                        
                        <h2 class="h4 mb-3 mt-5">7. Children's Privacy</h2>
                        <p>Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately.</p>
                        
                        <h2 class="h4 mb-3 mt-5">8. International Data Transfers</h2>
                        <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.</p>
                        
                        <h2 class="h4 mb-3 mt-5">9. Changes to This Policy</h2>
                        <p>We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on our website and updating the "Last updated" date.</p>
                        
                        <h2 class="h4 mb-3 mt-5">10. Contact Us</h2>
                        <p>If you have any questions about this privacy policy or our data practices, please contact us:</p>
                        <ul>
                            <li><strong>Email:</strong> <?php echo ADMIN_EMAIL; ?></li>
                            <li><strong>Phone:</strong> (555) 123-4567</li>
                            <li><strong>Address:</strong> 123 Hardware St, City, State</li>
                        </ul>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This privacy policy applies to the I-I Brothers Hardware Store website and services. By using our website, you consent to the collection and use of information as described in this policy.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 