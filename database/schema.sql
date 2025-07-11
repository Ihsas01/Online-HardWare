-- Create the database
CREATE DATABASE IF NOT EXISTS iibrothers;
USE iibrothers;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Create hired_tools table
CREATE TABLE IF NOT EXISTS hired_tools (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    user_id INT,
    hire_date DATE NOT NULL,
    return_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'active', 'returned', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create wishlist table
CREATE TABLE IF NOT EXISTS wishlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, first_name, last_name, role) VALUES
('admin', 'admin@iibrothers.com', '$2y$10$8K1p/a0dR1xqM8K3K3K3K.3K3K3K3K3K3K3K3K3K3K3K3K3K3K3K', 'Admin', 'User', 'admin');

-- Insert sample categories
INSERT INTO categories (name, description, icon) VALUES
('Hand Tools', 'Traditional hand tools for various tasks', 'fa-hammer'),
('Power Tools', 'Electric and battery-powered tools', 'fa-bolt'),
('Garden Tools', 'Tools for gardening and landscaping', 'fa-leaf'),
('Safety Equipment', 'Personal protective equipment', 'fa-shield-alt'),
('Plumbing Tools', 'Tools for plumbing work', 'fa-wrench'),
('Electrical Tools', 'Tools for electrical work', 'fa-plug'),
('Fasteners & Fixings', 'Screws, nails, and other fasteners', 'fa-screwdriver'),
('Specialty Tools', 'Specialized tools for specific tasks', 'fa-tools');

-- Insert sample products
INSERT INTO products (name, category_id, price, description, image, stock) VALUES
('Professional Hammer', 1, 29.99, 'Professional grade hammer with ergonomic handle', 'images/products/hammer.jpg', 50),
('Cordless Drill', 2, 89.99, '20V cordless drill with lithium-ion battery', 'images/products/drill.jpg', 30),
('Garden Shovel', 3, 24.99, 'Sturdy garden shovel with wooden handle', 'images/products/shovel.jpg', 40),
('Safety Goggles', 4, 14.99, 'Clear safety goggles with UV protection', 'images/products/goggles.jpg', 100),
('Pipe Wrench', 5, 34.99, 'Heavy-duty pipe wrench for plumbing work', 'images/products/pipe-wrench.jpg', 25),
('Multimeter', 6, 49.99, 'Digital multimeter for electrical testing', 'images/products/multimeter.jpg', 20),
('Screw Set', 7, 19.99, 'Assorted screw set with various sizes', 'images/products/screws.jpg', 200),
('Laser Level', 8, 79.99, 'Self-leveling laser level for precise measurements', 'images/products/laser-level.jpg', 15);

-- Insert sample hired tools
INSERT INTO hired_tools (product_id, user_id, hire_date, return_date, total_price, status) VALUES
(1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 29.99, 'active'),
(2, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 89.99, 'pending');

-- Insert sample orders
INSERT INTO orders (user_id, total_amount, status, shipping_address, payment_method, payment_status) VALUES
(1, 119.98, 'delivered', '123 Main St, City, Country', 'credit_card', 'completed'),
(1, 49.99, 'processing', '123 Main St, City, Country', 'paypal', 'pending');

-- Insert sample order items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 2, 29.99),
(1, 2, 1, 59.99),
(2, 3, 1, 49.99);

-- Insert sample cart items
INSERT INTO cart (user_id, product_id, quantity) VALUES
(1, 1, 1),
(1, 2, 1);

-- Insert sample wishlist items
INSERT INTO wishlist (user_id, product_id) VALUES
(1, 3),
(1, 4);

-- Insert sample contact messages
INSERT INTO contact_messages (name, email, subject, message) VALUES
('John Doe', 'john@example.com', 'Product Inquiry', 'I would like to know more about your power tools.'),
('Jane Smith', 'jane@example.com', 'Support Request', 'Need help with my recent order.'); 