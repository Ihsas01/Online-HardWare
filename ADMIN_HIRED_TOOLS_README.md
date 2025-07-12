# Admin Hired Tools Management

This document describes the admin functionality for managing hired tools in the I-I Brothers Hardware Store system.

## Features

### 1. View All Hired Tools
- Admins can view all hired tools with filtering options
- Filter by status (pending, approved, rejected, active, returned, cancelled)
- Filter by date range (today, this week, this month)
- View statistics for each status

### 2. Add New Hired Tools
- Admins can manually add new hired tool records
- Select from available products
- Select from registered customers
- Set hire and return dates
- Auto-calculate total price based on daily rate and duration
- Set initial status and admin notes

### 3. Edit Existing Hired Tools
- Admins can edit all fields of existing hired tool records
- Update product, customer, dates, price, status, and notes
- Validation ensures no date conflicts with other hires
- Maintains audit trail

### 4. Delete Hired Tools
- Admins can delete hired tool records
- Confirmation dialog prevents accidental deletions
- Complete removal from database

### 5. Approve/Reject Tools
- Approve pending tool requests
- Reject tool requests with optional notes
- Activate approved tools

## Access

To access the hired tools management:

1. Login as an admin user
2. Go to Admin Dashboard
3. Click "Manage Hired Tools" button
4. Or navigate directly to `/admin-hired-tools.php`

## File Structure

### Backend Files
- `php/process_hired_tool.php` - Handles all CRUD operations
- `php/get_hired_tool_details.php` - Returns tool details for editing
- `admin-hired-tools.php` - Main management interface

### Database
- `hired_tools` table stores all hired tool records
- Foreign keys to `products` and `users` tables
- Status tracking and admin approval fields

## API Endpoints

### Process Hired Tool (`php/process_hired_tool.php`)
- `action=add_hired_tool` - Add new hired tool
- `action=edit_hired_tool` - Edit existing hired tool
- `action=delete_hired_tool` - Delete hired tool
- `action=approve_tool` - Approve pending tool
- `action=reject_tool` - Reject pending tool
- `action=activate_tool` - Activate approved tool

### Get Tool Details (`php/get_hired_tool_details.php`)
- `id` parameter - Returns tool details for editing

## Security

- All operations require admin authentication
- Input validation on all forms
- SQL injection protection with prepared statements
- CSRF protection implemented
- Date conflict validation prevents double-booking

## Usage Examples

### Adding a New Hired Tool
1. Click "Add New Hired Tool" button
2. Select product and customer
3. Set hire and return dates
4. Review auto-calculated price
5. Set status and add notes if needed
6. Click "Save"

### Editing a Hired Tool
1. Click the edit (pencil) icon on any tool row
2. Modify any fields as needed
3. Click "Save" to update

### Deleting a Hired Tool
1. Click the delete (trash) icon on any tool row
2. Confirm deletion in the dialog
3. Tool is permanently removed

## Validation Rules

- Hire date must be before return date
- Product must be available
- Customer must exist in system
- No date conflicts with existing hires
- Total price must be positive
- Status must be valid enum value

## Error Handling

- User-friendly error messages
- Form validation with visual feedback
- Database error logging
- Graceful failure handling

## Future Enhancements

- Bulk operations (approve/reject multiple tools)
- Email notifications for status changes
- Advanced reporting and analytics
- Calendar view of tool availability
- Integration with inventory management 