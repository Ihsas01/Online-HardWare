    <!-- Modern Footer -->
    <footer class="modern-footer">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <!-- Company Information -->
                    <div class="col-lg-4 col-md-6 footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-tools"></i>
                            I-I Brothers
                        </h5>
                        <p class="footer-description">
                            Your trusted hardware store since 1995. We provide quality tools, equipment, and expert advice for all your hardware needs.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-link interactive" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link interactive" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link interactive" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link interactive" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link interactive" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-4 col-md-6 footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-link"></i>
                            Quick Links
                        </h5>
                        <ul class="footer-links">
                            <li class="footer-link">
                                <a href="<?php echo $assetPath; ?>about.php" class="interactive">
                                    <i class="fas fa-info-circle"></i>
                                    <span>About Us</span>
                                </a>
                            </li>
                            <li class="footer-link">
                                <a href="<?php echo $assetPath; ?>products.php" class="interactive">
                                    <i class="fas fa-box"></i>
                                    <span>Products</span>
                                </a>
                            </li>
                            <li class="footer-link">
                                <a href="<?php echo $assetPath; ?>hired-tools.php" class="interactive">
                                    <i class="fas fa-wrench"></i>
                                    <span>Hired Tools</span>
                                </a>
                            </li>
                            <li class="footer-link">
                                <a href="<?php echo $assetPath; ?>contact.php" class="interactive">
                                    <i class="fas fa-envelope"></i>
                                    <span>Contact</span>
                                </a>
                            </li>
                            <li class="footer-link">
                                <a href="<?php echo $assetPath; ?>privacy.php" class="interactive">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Privacy Policy</span>
                                </a>
                            </li>
                            <li class="footer-link">
                                <a href="<?php echo $assetPath; ?>terms.php" class="interactive">
                                    <i class="fas fa-file-contract"></i>
                                    <span>Terms & Conditions</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="col-lg-4 col-md-6 footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-phone"></i>
                            Contact Us
                        </h5>
                        <ul class="contact-info">
                            <li class="contact-item interactive">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Phone Number</div>
                                    <div class="contact-value">(555) 123-4567</div>
                                </div>
                            </li>
                            <li class="contact-item interactive">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Email Address</div>
                                    <div class="contact-value"><?php echo ADMIN_EMAIL; ?></div>
                                </div>
                            </li>
                            <li class="contact-item interactive">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Business Address</div>
                                    <div class="contact-value">123 Hardware St, City, State</div>
                                </div>
                            </li>
                            <li class="contact-item interactive">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Business Hours</div>
                                    <div class="contact-value">Mon-Sat: 8:00 AM - 8:00 PM</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        <i class="fas fa-copyright me-1"></i>
                        <?php echo date('Y'); ?> I-I Brothers. All rights reserved.
                    </div>
                    <div class="footer-bottom-links">
                        <a href="<?php echo $assetPath; ?>privacy.php" class="interactive">Privacy Policy</a>
                        <a href="<?php echo $assetPath; ?>terms.php" class="interactive">Terms & Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $assetPath; ?>js/main.js"></script>
    
    <!-- JavaScript for enhanced footer interactions -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation class
        const footer = document.querySelector('.modern-footer');
        if (footer) {
            footer.classList.add('loading');
            setTimeout(() => {
                footer.classList.add('loaded');
            }, 200);
        }
        
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
        
        // Enhanced social link animations
        const socialLinks = document.querySelectorAll('.social-link');
        socialLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'scale(1.2) rotate(5deg)';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'scale(1) rotate(0deg)';
                }
            });
        });
        
        // Contact item hover effects
        const contactItems = document.querySelectorAll('.contact-item');
        contactItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const icon = this.querySelector('.contact-icon');
                if (icon) {
                    icon.style.transform = 'scale(1.1) rotate(5deg)';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const icon = this.querySelector('.contact-icon');
                if (icon) {
                    icon.style.transform = 'scale(1) rotate(0deg)';
                }
            });
        });
        
        // Footer link hover effects
        const footerLinks = document.querySelectorAll('.footer-link a');
        footerLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'scale(1.1)';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'scale(1)';
                }
            });
        });
        
        // Scroll to top functionality
        const scrollToTopBtn = document.createElement('button');
        scrollToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollToTopBtn.className = 'scroll-to-top interactive';
        scrollToTopBtn.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--footer-accent);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        `;
        
        document.body.appendChild(scrollToTopBtn);
        
        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollToTopBtn.style.opacity = '1';
                scrollToTopBtn.style.visibility = 'visible';
            } else {
                scrollToTopBtn.style.opacity = '0';
                scrollToTopBtn.style.visibility = 'hidden';
            }
        });
        
        // Scroll to top functionality
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Add hover effect to scroll to top button
        scrollToTopBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.boxShadow = '0 6px 16px rgba(59, 130, 246, 0.4)';
        });
        
        scrollToTopBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = '0 4px 12px rgba(59, 130, 246, 0.3)';
        });
    });
    </script>
</body>
</html> 