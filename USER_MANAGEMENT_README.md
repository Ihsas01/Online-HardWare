# User Management System

This document describes the user management functionality for administrators in the I-I Brothers Hardware Store system.

## 🎯 **Overview**

Admins can now manage all users in the system, including:
- **Add new users** (customers and admins)
- **Edit existing users** (update information, change roles)
- **Delete users** (with safety checks)
- **View user statistics** and search/filter users

## 🔧 **Features**

### **1. User Management Interface**
- **Complete user list** with search and filtering
- **Statistics dashboard** showing total users, admins, and customers
- **Responsive design** for all devices
- **Real-time updates** after operations

### **2. Add New Users**
- **Create customers** for tool hiring
- **Create admin users** for system management
- **Form validation** for all required fields
- **Duplicate prevention** (username and email)

### **3. Edit Existing Users**
- **Update user information** (name, email, phone, address)
- **Change user roles** (customer ↔ admin)
- **Optional password updates** (keep existing or set new)
- **Maintain data integrity** with validation

### **4. Delete Users**
- **Safe deletion** with confirmation dialogs
- **Prevent self-deletion** (admin can't delete their own account)
- **Prevent last admin deletion** (system must have at least one admin)
- **Cascade protection** (check for related data)

## 🛡️ **Security Features**

### **Access Control**
- ✅ **Admin-only access** - Only logged-in admins can access
- ✅ **Session validation** - Checks admin role before operations
- ✅ **CSRF protection** - Form validation and token checking

### **Data Validation**
- ✅ **Required field validation** - All essential fields must be provided
- ✅ **Email format validation** - Proper email format required
- ✅ **Username uniqueness** - No duplicate usernames allowed
- ✅ **Email uniqueness** - No duplicate emails allowed
- ✅ **Password strength** - Secure password hashing

### **Safety Checks**
- ✅ **Self-deletion prevention** - Admin cannot delete their own account
- ✅ **Last admin protection** - Cannot delete the last admin user
- ✅ **Role validation** - Only valid roles (admin/customer) accepted
- ✅ **Input sanitization** - All user inputs are sanitized

## 📊 **User Statistics**

The system provides real-time statistics:
- **Total Users** - Count of all registered users
- **Admin Users** - Count of users with admin role
- **Customer Users** - Count of users with customer role

## 🔍 **Search & Filter**

### **Search Functionality**
- **Search by name** - First name, last name, or full name
- **Search by email** - Email address matching
- **Search by username** - Username matching
- **Real-time results** - Instant search results

### **Filter Options**
- **Role filter** - Show only admins or customers
- **Clear filters** - Reset all search and filter options
- **Combined search** - Use search and filters together

## 📋 **User Information**

### **User Fields**
- **Username** - Unique login identifier
- **Email** - User's email address
- **First Name** - User's first name
- **Last Name** - User's last name
- **Phone** - Contact phone number (optional)
- **Address** - User's address (optional)
- **Role** - User role (admin/customer)
- **Created Date** - Account creation timestamp

### **Role Types**
- **Admin** - Full system access and management
- **Customer** - Standard user access for tool hiring

## 🎨 **User Interface**

### **Main Dashboard**
- **User statistics cards** - Quick overview of user counts
- **Search and filter bar** - Easy user finding
- **User table** - Complete user list with actions
- **Add user button** - Quick access to add new users

### **User Table Features**
- **User information display** - Name, email, role, phone
- **Role badges** - Color-coded role indicators
- **Action buttons** - Edit and delete for each user
- **Responsive design** - Works on all screen sizes

### **Modal Forms**
- **Add user modal** - Complete form for new users
- **Edit user modal** - Pre-filled form for editing
- **Validation feedback** - Real-time form validation
- **Password handling** - Optional password updates

## 🔧 **Technical Implementation**

### **Files Created/Modified**

#### **Frontend Files**
- `admin/manage-users.php` - Main user management interface
- `admin/dashboard.php` - Added user management links

#### **Backend Files**
- `php/process_user.php` - Handles all user CRUD operations
- `php/get_user_details.php` - Returns user data for editing

### **API Endpoints**

#### **User Operations**
- `action=add_user` - Add new user
- `action=edit_user` - Edit existing user
- `action=delete_user` - Delete user

#### **Data Retrieval**
- `get_user_details.php?id=X` - Get user details for editing

### **Database Operations**
- **User creation** - INSERT with password hashing
- **User updates** - UPDATE with optional password change
- **User deletion** - DELETE with safety checks
- **User queries** - SELECT with search and filtering

## 🚀 **Usage Examples**

### **Adding a New Customer**
1. Admin clicks "Add New User"
2. Fills in user details (name, email, password)
3. Sets role to "Customer"
4. Clicks "Save"
5. User is created and can immediately log in

### **Adding a New Admin**
1. Admin clicks "Add New User"
2. Fills in admin details
3. Sets role to "Admin"
4. Clicks "Save"
5. New admin can access admin dashboard

### **Editing a User**
1. Admin clicks edit button on user row
2. Modal opens with user's current information
3. Admin modifies desired fields
4. Optionally changes password
5. Clicks "Save" to update

### **Deleting a User**
1. Admin clicks delete button on user row
2. Confirmation dialog appears
3. Admin confirms deletion
4. User is removed from system
5. Related data is handled appropriately

## ⚠️ **Safety Features**

### **Deletion Protections**
- **Self-deletion prevention** - Admin cannot delete their own account
- **Last admin protection** - System prevents deleting the last admin
- **Confirmation dialogs** - Double confirmation for deletions
- **Data integrity** - Checks for related data before deletion

### **Role Management**
- **Role validation** - Only valid roles accepted
- **Admin count tracking** - Monitors number of admin users
- **Role change safety** - Prevents removing all admins

## 🔮 **Future Enhancements**

### **Planned Features**
- 📧 **Email notifications** - Notify users of account changes
- 📊 **User activity tracking** - Monitor user login and activity
- 🔐 **Password reset functionality** - Allow admins to reset passwords
- 📋 **Bulk operations** - Select multiple users for batch actions

### **Potential Improvements**
- 🔍 **Advanced search** - More sophisticated search options
- 📈 **User analytics** - Detailed user behavior reports
- 🎯 **User permissions** - Granular permission system
- 📱 **Mobile optimization** - Enhanced mobile interface

## 🛠️ **Troubleshooting**

### **Common Issues**
1. **User not found** - Check if user ID is valid
2. **Duplicate username/email** - Verify uniqueness before adding
3. **Password issues** - Ensure password meets requirements
4. **Role assignment errors** - Verify role is valid

### **Debug Steps**
1. Check browser console for JavaScript errors
2. Verify database connections
3. Check PHP error logs
4. Validate form data submission

## 📞 **Support**

For technical support or questions about the user management system:
- Check the database schema in `database/schema.sql`
- Review the admin documentation in `ADMIN_HIRED_TOOLS_README.md`
- Test the functionality using the provided examples

The user management system is now fully functional and ready for production use! 🎉 