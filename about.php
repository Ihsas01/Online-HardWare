<?php
require_once 'php/config.php';
$pageTitle = 'About Us';
$currentPage = 'about';

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="about-hero">
    <div class="about-hero-bg"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="about-hero-content">
                    <div class="about-badge mb-4">
                        <span class="badge bg-primary-gradient">Est. 1995</span>
                    </div>
                    <h1 class="about-hero-title">
                        Building Dreams,
                        <span class="text-gradient">One Tool at a Time</span>
                    </h1>
                    <p class="about-hero-subtitle">
                        For over 25 years, I-I Brothers has been the trusted partner for professionals and DIY enthusiasts alike. 
                        We don't just sell tools – we empower you to create, build, and achieve your vision.
                    </p>
                    <div class="about-hero-stats">
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                            <div class="stat-number">25+</div>
                            <div class="stat-label">Years</div>
                        </div>
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Happy Customers</div>
                        </div>
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="600">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Products</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="about-hero-visual">
                    <div class="floating-tools">
                        <div class="tool-icon tool-1">
                            <i class="fas fa-hammer"></i>
                        </div>
                        <div class="tool-icon tool-2">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <div class="tool-icon tool-3">
                            <i class="fas fa-screwdriver"></i>
                        </div>
                        <div class="tool-icon tool-4">
                            <i class="fas fa-drill"></i>
                        </div>
                        <div class="tool-icon tool-5">
                            <i class="fas fa-saw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="about-story py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="about-story-content">
                    <div class="section-badge mb-4">
                        <span class="badge bg-secondary-gradient">Our Journey</span>
                    </div>
                    <h2 class="section-title">The I-I Brothers Story</h2>
                    <p class="section-subtitle">
                        From humble beginnings to becoming the region's most trusted hardware destination
                    </p>
                    <div class="timeline">
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                            <div class="timeline-marker">1995</div>
                            <div class="timeline-content">
                                <h4>Foundation</h4>
                                <p>Two brothers with a vision opened a small hardware store in the heart of our community.</p>
                            </div>
                        </div>
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                            <div class="timeline-marker">2005</div>
                            <div class="timeline-content">
                                <h4>Expansion</h4>
                                <p>Introduced tool rental services, making professional equipment accessible to everyone.</p>
                            </div>
                        </div>
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="600">
                            <div class="timeline-marker">2015</div>
                            <div class="timeline-content">
                                <h4>Innovation</h4>
                                <p>Launched our online platform, bringing our expertise to customers nationwide.</p>
                            </div>
                        </div>
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="800">
                            <div class="timeline-marker">2020</div>
                            <div class="timeline-content">
                                <h4>Today</h4>
                                <p>Leading the industry with cutting-edge tools and exceptional customer service.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="about-story-image">
                    <div class="image-stack">
                        <div class="image-item image-1">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Hardware Store">
                        </div>
                        <div class="image-item image-2">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Tools">
                        </div>
                        <div class="image-item image-3">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Customer Service">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="about-values py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5" data-aos="fade-up">
                <div class="section-badge mb-4">
                    <span class="badge bg-primary-gradient">Our Values</span>
                </div>
                <h2 class="section-title">What Drives Us Forward</h2>
                <p class="section-subtitle">
                    These core principles guide everything we do and every decision we make
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Customer First</h3>
                    <p>Your success is our success. We go above and beyond to ensure you have everything you need to complete your projects.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Quality & Safety</h3>
                    <p>We maintain the highest standards of quality and safety in all our products and services. Your safety is non-negotiable.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Integrity</h3>
                    <p>We conduct business with honesty, transparency, and respect. Your trust is our most valuable asset.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="800">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>We continuously evolve and adapt to bring you the latest tools and technologies in the industry.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="about-services py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5" data-aos="fade-up">
                <div class="section-badge mb-4">
                    <span class="badge bg-success-gradient">Our Services</span>
                </div>
                <h2 class="section-title">Comprehensive Solutions</h2>
                <p class="section-subtitle">
                    From basic tools to professional equipment, we have everything you need
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Tool Sales</h3>
                    <p>Comprehensive selection of hand tools, power tools, and professional equipment from top manufacturers.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Hand & Power Tools</li>
                        <li><i class="fas fa-check"></i> Garden Equipment</li>
                        <li><i class="fas fa-check"></i> Safety Gear</li>
                        <li><i class="fas fa-check"></i> Hardware Supplies</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="service-card featured">
                    <div class="service-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Tool Rental</h3>
                    <p>Access professional-grade equipment without the investment. Flexible rental periods and competitive rates.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Professional Equipment</li>
                        <li><i class="fas fa-check"></i> Flexible Periods</li>
                        <li><i class="fas fa-check"></i> Competitive Rates</li>
                        <li><i class="fas fa-check"></i> Safety Inspections</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Expert Support</h3>
                    <p>Our knowledgeable team provides expert advice, technical support, and project guidance.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Expert Advice</li>
                        <li><i class="fas fa-check"></i> Technical Support</li>
                        <li><i class="fas fa-check"></i> Project Guidance</li>
                        <li><i class="fas fa-check"></i> Training Sessions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="about-team py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5" data-aos="fade-up">
                <div class="section-badge mb-4">
                    <span class="badge bg-info-gradient">Our Team</span>
                </div>
                <h2 class="section-title">Meet Our Experts</h2>
                <p class="section-subtitle">
                    Passionate professionals dedicated to helping you succeed
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Team Member">
                    </div>
                    <div class="team-info">
                        <h4>Ahmed Ibrahim</h4>
                        <p class="team-role">Founder & CEO</p>
                        <p class="team-bio">25+ years of experience in hardware retail and customer service.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Team Member">
                    </div>
                    <div class="team-info">
                        <h4>Sarah Johnson</h4>
                        <p class="team-role">Technical Specialist</p>
                        <p class="team-bio">Expert in power tools and equipment maintenance.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Team Member">
                    </div>
                    <div class="team-info">
                        <h4>Mike Chen</h4>
                        <p class="team-role">Sales Manager</p>
                        <p class="team-bio">Specializes in helping customers find the perfect tools.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="800">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Team Member">
                    </div>
                    <div class="team-info">
                        <h4>David Rodriguez</h4>
                        <p class="team-role">Rental Coordinator</p>
                        <p class="team-bio">Ensures smooth rental operations and customer satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="about-contact py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5" data-aos="fade-up">
                <div class="section-badge mb-4">
                    <span class="badge bg-warning-gradient">Visit Us</span>
                </div>
                <h2 class="section-title">Ready to Get Started?</h2>
                <p class="section-subtitle">
                    Visit our store or get in touch with our team
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Store Hours</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <span class="day">Monday - Friday</span>
                            <span class="time">8:00 AM - 8:00 PM</span>
                        </div>
                        <div class="contact-item">
                            <span class="day">Saturday</span>
                            <span class="time">8:00 AM - 6:00 PM</span>
                        </div>
                        <div class="contact-item">
                            <span class="day">Sunday</span>
                            <span class="time">10:00 AM - 4:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Location</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <span class="label">Address</span>
                            <span class="value">123 Main Street, Colombo, Sri Lanka</span>
                        </div>
                        <div class="contact-item">
                            <span class="label">Phone</span>
                            <span class="value">076 xxx xxxx</span>
                        </div>
                        <div class="contact-item">
                            <span class="label">Email</span>
                            <span class="value">mohamedihsas001@gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Get in Touch</h3>
                    <p>Have questions? Our team is here to help you find the perfect tools for your project.</p>
                    <div class="contact-actions">
                        <a href="contact.php" class="btn btn-primary">
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
</section>

<!-- About Page CSS -->
<style>
/* About Hero Section */
.about-hero {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    overflow: hidden;
}

.about-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.about-hero-content {
    position: relative;
    z-index: 2;
}

.about-badge {
    display: inline-block;
}

.badge {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 2rem;
}

.bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.bg-secondary-gradient {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.bg-success-gradient {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.bg-info-gradient {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.bg-warning-gradient {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.about-hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.text-gradient {
    background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.about-hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.about-hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #ffd89b;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.about-hero-visual {
    position: relative;
    height: 500px;
}

.floating-tools {
    position: relative;
    width: 100%;
    height: 100%;
}

.tool-icon {
    position: absolute;
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: float 6s ease-in-out infinite;
}

.tool-1 { top: 20%; left: 20%; animation-delay: 0s; }
.tool-2 { top: 40%; right: 30%; animation-delay: 1s; }
.tool-3 { bottom: 30%; left: 40%; animation-delay: 2s; }
.tool-4 { top: 60%; right: 20%; animation-delay: 3s; }
.tool-5 { bottom: 20%; right: 40%; animation-delay: 4s; }

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

/* Story Section */
.about-story {
    background: white;
}

.section-badge {
    display: inline-block;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2d3748;
}

.section-subtitle {
    font-size: 1.125rem;
    color: #718096;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -1rem;
    top: 0;
    width: 2rem;
    height: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

.timeline-content h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2d3748;
}

.timeline-content p {
    color: #718096;
    line-height: 1.6;
}

.image-stack {
    position: relative;
    height: 400px;
}

.image-item {
    position: absolute;
    width: 200px;
    height: 150px;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.image-item:hover {
    transform: scale(1.05);
}

.image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-1 { top: 0; left: 0; }
.image-2 { top: 50px; right: 0; }
.image-3 { bottom: 0; left: 50px; }

/* Values Section */
.about-values {
    background: #f7fafc;
}

.value-card {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
}

.value-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2d3748;
}

.value-card p {
    color: #718096;
    line-height: 1.6;
}

/* Services Section */
.service-card {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.service-card:hover::before {
    transform: scaleX(1);
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.service-card.featured {
    border: 2px solid #667eea;
    transform: scale(1.05);
}

.service-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
}

.service-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2d3748;
}

.service-card p {
    color: #718096;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.service-features {
    list-style: none;
    padding: 0;
}

.service-features li {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: #718096;
}

.service-features i {
    color: #48bb78;
    margin-right: 0.5rem;
    font-size: 0.875rem;
}

/* Team Section */
.about-team {
    background: #f7fafc;
}

.team-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.team-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.team-avatar {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.team-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.team-card:hover .team-avatar img {
    transform: scale(1.1);
}

.team-info {
    padding: 1.5rem;
    text-align: center;
}

.team-info h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2d3748;
}

.team-role {
    color: #667eea;
    font-weight: 500;
    margin-bottom: 1rem;
}

.team-bio {
    color: #718096;
    font-size: 0.875rem;
    line-height: 1.6;
}

/* Contact Section */
.about-contact {
    background: white;
}

.contact-card {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.contact-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
}

.contact-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2d3748;
}

.contact-details {
    margin-bottom: 1.5rem;
}

.contact-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-item .day,
.contact-item .label {
    font-weight: 600;
    color: #2d3748;
}

.contact-item .time,
.contact-item .value {
    color: #718096;
}

.contact-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline-primary {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-hero-title {
        font-size: 2.5rem;
    }
    
    .about-hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .floating-tools {
        display: none;
    }
    
    .image-stack {
        height: 300px;
    }
    
    .image-item {
        width: 150px;
        height: 100px;
    }
}

@media (max-width: 576px) {
    .about-hero-title {
        font-size: 2rem;
    }
    
    .about-hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .contact-actions {
        flex-direction: column;
    }
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}

/* Loading Animation */
.about-hero,
.about-story,
.about-values,
.about-services,
.about-team,
.about-contact {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hover Effects */
.value-card,
.service-card,
.team-card,
.contact-card {
    cursor: pointer;
}

/* Focus States */
.btn:focus,
.value-card:focus,
.service-card:focus,
.team-card:focus,
.contact-card:focus {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}
</style>

<!-- About Page JavaScript -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialize AOS
AOS.init({
    duration: 1000,
    easing: 'ease-out-cubic',
    once: true,
    offset: 100
});

// Smooth scroll for navigation links
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

// Parallax effect for hero section
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.about-hero-bg');
    if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Floating tools animation
const floatingTools = document.querySelector('.floating-tools');
if (floatingTools) {
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const tools = floatingTools.querySelectorAll('.tool-icon');
        
        tools.forEach((tool, index) => {
            const speed = 0.5 + (index * 0.1);
            tool.style.transform = `translateY(${scrolled * speed * 0.1}px) rotate(${scrolled * 0.01}deg)`;
        });
    });
}

// Intersection Observer for enhanced animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
        }
    });
}, observerOptions);

// Observe elements for animation
document.querySelectorAll('.value-card, .service-card, .team-card, .contact-card').forEach(el => {
    observer.observe(el);
});

// Add loading animation class
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.about-hero, .about-story, .about-values, .about-services, .about-team, .about-contact');
    sections.forEach((section, index) => {
        section.style.animationDelay = `${index * 0.2}s`;
    });
});
</script>

<?php include 'includes/footer.php'; ?> 