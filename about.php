<?php
require_once 'php/config.php';
$pageTitle = 'About Us';
$currentPage = 'about';

include 'includes/header.php';
?>

<!-- About Us Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="mb-4">About I-I Brothers</h1>
                <p class="text-muted mb-4">Your trusted hardware partner since 1995</p>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Our Story</h2>
                        <p>Founded in 1995 by the Ibrahim brothers, I-I Brothers Hardware Store has been serving our community with quality tools, equipment, and exceptional customer service for over 25 years. What started as a small family business has grown into one of the most trusted names in hardware retail.</p>
                        
                        <p>Our journey began with a simple mission: to provide our customers with the best tools and equipment they need to complete their projects successfully. Over the years, we've expanded our offerings to include not just sales, but also tool rental services, making professional-grade equipment accessible to everyone.</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                                <h3 class="h5">Quality Products</h3>
                                <p>We carry only the highest quality tools and equipment from trusted manufacturers. Every product in our store is carefully selected to meet our strict quality standards.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-success mb-3"></i>
                                <h3 class="h5">Expert Service</h3>
                                <p>Our knowledgeable staff is here to help you find the right tools for your project. We provide expert advice and support to ensure your success.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Our Services</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="h5">Tool Sales</h3>
                                <ul>
                                    <li>Hand tools and power tools</li>
                                    <li>Garden and landscaping equipment</li>
                                    <li>Safety equipment and protective gear</li>
                                    <li>Plumbing and electrical supplies</li>
                                    <li>Fasteners, adhesives, and hardware</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h3 class="h5">Tool Rental</h3>
                                <ul>
                                    <li>Professional-grade equipment rental</li>
                                    <li>Flexible rental periods</li>
                                    <li>Competitive daily and weekly rates</li>
                                    <li>Equipment maintenance and safety checks</li>
                                    <li>Expert guidance on tool usage</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Our Values</h2>
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                                <h4 class="h6">Customer First</h4>
                                <p class="small">Your satisfaction is our top priority. We go above and beyond to meet your needs.</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                <h4 class="h6">Quality & Safety</h4>
                                <p class="small">We maintain the highest standards of quality and safety in all our products and services.</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="fas fa-handshake fa-2x text-info mb-2"></i>
                                <h4 class="h6">Integrity</h4>
                                <p class="small">We conduct business with honesty, transparency, and respect for our customers and community.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Our Team</h2>
                        <p>Our team consists of experienced professionals who are passionate about tools and committed to helping our customers succeed. From our sales associates to our technical support staff, everyone at I-I Brothers is dedicated to providing you with the best possible service.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h3 class="h5">Sales Team</h3>
                                <p>Our knowledgeable sales team can help you find the perfect tools for your project, whether you're a professional contractor or a DIY enthusiast.</p>
                            </div>
                            <div class="col-md-6">
                                <h3 class="h5">Technical Support</h3>
                                <p>Our technical experts can provide guidance on tool usage, maintenance, and troubleshooting to ensure you get the most out of your equipment.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Visit Us</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="h5">Store Hours</h3>
                                <ul class="list-unstyled">
                                    <li><strong>Monday - Friday:</strong> 8:00 AM - 8:00 PM</li>
                                    <li><strong>Saturday:</strong> 8:00 AM - 6:00 PM</li>
                                    <li><strong>Sunday:</strong> 10:00 AM - 4:00 PM</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h3 class="h5">Contact Information</h3>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-phone me-2"></i>(555) 123-4567</li>
                                    <li><i class="fas fa-envelope me-2"></i><?php echo ADMIN_EMAIL; ?></li>
                                    <li><i class="fas fa-map-marker-alt me-2"></i>123 Hardware St, City, State</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="contact.php" class="btn btn-primary me-2">
                                <i class="fas fa-envelope me-2"></i>Contact Us
                            </a>
                            <a href="products.php" class="btn btn-outline-primary">
                                <i class="fas fa-tools me-2"></i>Browse Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 