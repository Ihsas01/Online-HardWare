# Hired Tools System

## Overview
The Hired Tools System allows customers to hire tools from the hardware store with admin approval workflow. The system includes booking, approval, rejection, and return functionality.

## Features

### For Customers:
- **Browse Tools**: View all available tools on the products page
- **Hire Tools**: Click "Hire" button on any product to book it
- **Select Dates**: Choose hire and return dates with automatic price calculation
- **View Status**: Check the status of their hire requests (pending, approved, rejected, active, returned)
- **Request Returns**: Request to return active tools
- **View History**: See all their hired tools with detailed information

### For Admins:
- **Manage All Hires**: View all hired tools across all customers
- **Approve/Reject**: Approve or reject pending hire requests
- **Add Notes**: Add admin notes when approving or rejecting
- **Activate Tools**: Move approved tools to active status
- **View Details**: Detailed view of each hire with customer information
- **Statistics**: Dashboard with hire statistics
- **Filtering**: Filter by status and date ranges

## System Workflow

### 1. Tool Booking Process
1. Customer clicks "Hire" button on product page
2. Modal opens with tool details and date selection
3. Customer selects hire and return dates
4. System calculates total price (daily rate × number of days)
5. Customer submits hire request
6. Request is saved with "pending" status

### 2. Admin Approval Process
1. Admin views pending requests in admin panel
2. Admin can approve or reject each request
3. Admin can add notes explaining their decision
4. System updates status to "approved" or "rejected"
5. Admin can activate approved tools (status becomes "active")

### 3. Tool Return Process
1. Customer can request return of active tools
2. System updates status to "returned"
3. Tool becomes available for other customers

## Database Schema

### hired_tools Table
```sql
CREATE TABLE hired_tools (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    user_id INT,
    hire_date DATE NOT NULL,
    return_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'active', 'returned', 'cancelled') DEFAULT 'pending',
    admin_notes TEXT,
    admin_approved_by INT,
    admin_approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (admin_approved_by) REFERENCES users(id)
);
```

## Status Meanings

- **pending**: Customer has submitted hire request, waiting for admin approval
- **approved**: Admin has approved the request, tool is ready for activation
- **rejected**: Admin has rejected the request
- **active**: Tool is currently hired and in use
- **returned**: Tool has been returned by customer
- **cancelled**: Hire request was cancelled

## Files Overview

### Core Files:
- `hired-tools.php` - Main page showing hired tools (different views for admin/customer)
- `admin-hired-tools.php` - Admin management interface with statistics and filtering
- `php/process_hired_tool.php` - Backend API for all hire operations
- `php/get_tool_details.php` - API for fetching detailed tool information

### Database:
- `database/schema.sql` - Updated schema with new columns
- `php/update_hired_tools_schema.php` - Script to update existing database

### Integration:
- `products.php` - Updated with hire buttons and modal
- `php/config.php` - Contains helper functions for authentication

## Setup Instructions

### 1. Update Database Schema
Run the schema update script:
```bash
php php/update_hired_tools_schema.php
```

### 2. Test the System
1. **As a Customer:**
   - Browse products and click "Hire" button
   - Select dates and submit hire request
   - Check status in "My Hired Tools" page

2. **As an Admin:**
   - Login as admin user
   - Go to "Hired Tools" page to see all requests
   - Use "Manage Hired Tools" for detailed management
   - Approve/reject pending requests
   - Activate approved tools

## API Endpoints

### POST /php/process_hired_tool.php
Handles all hire operations:

- `action=book_tool` - Customer books a tool
- `action=approve_tool` - Admin approves a request
- `action=reject_tool` - Admin rejects a request
- `action=activate_tool` - Admin activates approved tool
- `action=return_tool` - Customer returns active tool

### GET /php/get_tool_details.php?id={tool_id}
Returns detailed tool information for admin view.

## Security Features

- **Authentication Required**: All operations require user login
- **Admin Authorization**: Admin functions require admin role
- **CSRF Protection**: Built-in CSRF token validation
- **Input Validation**: All inputs are sanitized and validated
- **SQL Injection Prevention**: Prepared statements used throughout

## Error Handling

The system includes comprehensive error handling:
- Database connection errors
- Invalid input validation
- Authorization checks
- Duplicate booking prevention
- Date validation

## Future Enhancements

Potential improvements:
- Email notifications for status changes
- Payment integration
- Tool availability calendar
- Bulk operations for admins
- Customer reviews and ratings
- Automated reminders for returns

## Troubleshooting

### Common Issues:

1. **"Tool is already hired" error**
   - Check if tool is available for selected dates
   - Verify no overlapping bookings exist

2. **Admin approval not working**
   - Ensure user has admin role
   - Check database permissions

3. **Modal not opening**
   - Verify Bootstrap JS is loaded
   - Check for JavaScript errors in console

4. **Database connection issues**
   - Verify database credentials in config.php
   - Check if MySQL service is running 