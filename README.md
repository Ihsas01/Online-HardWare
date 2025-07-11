# I-I Brothers Hardware Tools Website

A comprehensive e-commerce platform for hardware tools, featuring product listings, tool hiring services, and user management.

## Features

- 🛍️ **Product Management**
  - Browse hardware tools and equipment
  - Detailed product information and specifications
  - Product categories and search functionality
  - Product images and descriptions

- 🔧 **Tool Hiring System**
  - Hire tools for specific durations
  - View available tools for hire
  - Track hiring history
  - Manage hiring requests

- 👤 **User Management**
  - User registration and authentication
  - User profiles and order history
  - Admin panel for site management
  - Role-based access control

- 🛒 **Shopping Features**
  - Shopping cart functionality
  - Wishlist management
  - Order processing
  - Payment integration

## Technical Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHP dependencies)
- Modern web browser

## Installation

1. **Clone the repository**
   ```bash
   git clone [repository-url]
   cd hardware-tools-website
   ```

2. **Set up the database**
   - Create a new MySQL database
   - Import the database schema from `database/schema.sql`
   - Configure database connection in `php/config.php`

3. **Configure the application**
   - Copy `php/config.example.php` to `php/config.php`
   - Update the configuration settings:
     - Database credentials
     - Site URL
     - Email settings
     - Other environment variables

4. **Set up the web server**
   - Point your web server to the project's root directory
   - Ensure the `uploads` directory is writable
   - Configure URL rewriting if using Apache

5. **Install dependencies**
   ```bash
   composer install
   ```

## Directory Structure

```
├── admin/              # Admin panel files
├── css/               # Stylesheets
├── database/          # Database schema and migrations
├── includes/          # Common PHP includes
├── js/                # JavaScript files
├── php/               # PHP classes and functions
├── uploads/           # Uploaded files
├── vendor/            # Composer dependencies
├── index.php          # Homepage
├── config.php         # Configuration file
└── README.md          # This file
```

## Usage

1. **Admin Access**
   - Access the admin panel at `/admin`
   - Default admin credentials:
     - Username: admin
     - Password: (set during installation)

2. **User Features**
   - Register/Login at `/register.php` and `/login.php`
   - Browse products at `/products.php`
   - View hired tools at `/hired-tools.php`
   - Manage cart at `/cart.php`
   - View orders at `/orders.php`

## Security

- All passwords are hashed using secure algorithms
- SQL injection prevention using prepared statements
- XSS protection implemented
- CSRF protection for forms
- Secure session management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please contact:
- Email: support@iibrothers.com
- Phone: [Your Support Phone Number]

## Acknowledgments

- Bootstrap for the frontend framework
- Font Awesome for icons
- All contributors who have helped with the project 