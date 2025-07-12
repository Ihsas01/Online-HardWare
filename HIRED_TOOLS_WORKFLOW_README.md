# Hired Tools Workflow - Complete System

This document explains the complete workflow for the hired tools system in the I-I Brothers Hardware Store.

## 🎯 **Complete Workflow Overview**

### **1. Admin Setup Phase**
1. **Admin adds hireable tools** through admin dashboard
2. **Admin manages available products** for hiring
3. **Admin can add/edit/delete hired tool records** directly

### **2. Guest/User Viewing Phase**
1. **Guests can view** all available tools for hiring
2. **Guests see registration prompts** to book tools
3. **Logged-in users can book** tools directly

### **3. Booking & Approval Phase**
1. **Users book tools** (creates pending requests)
2. **Admin reviews** and approves/rejects bookings
3. **System prevents double-booking** automatically
4. **Admin can activate** approved tools

### **4. Tool Usage & Return Phase**
1. **Users can request returns** for active tools
2. **Admin manages** tool status and availability

---

## 📋 **Detailed Workflow Steps**

### **Step 1: Admin Setup**
```
Admin Dashboard → Manage Products → Add Hireable Tools
```

**What Admin Does:**
- Login as admin user
- Go to Admin Dashboard
- Click "Manage Products" to add/edit products
- Set products as available for hiring
- Optionally add direct hired tool records via "Manage Hired Tools"

### **Step 2: Guest/User Browsing**
```
hired-tools.php → View Available Tools → Search/Filter
```

**What Users See:**
- All available tools for hiring
- Search and filter functionality
- Daily rates and availability
- Registration/login prompts for guests

### **Step 3: User Registration (if needed)**
```
Guest → Register → Login → Book Tools
```

**Registration Process:**
- Guests must register to book tools
- Email verification (if implemented)
- User profile creation

### **Step 4: Tool Booking**
```
User → Select Tool → Choose Dates → Submit Booking
```

**Booking Process:**
- User selects tool to hire
- Chooses hire and return dates
- System auto-calculates total price
- User submits booking request
- Status becomes "pending"

### **Step 5: Admin Review**
```
Admin → Review Pending Requests → Approve/Reject
```

**Admin Actions:**
- View all pending bookings
- Check tool availability for requested dates
- Approve or reject with notes
- Activate approved tools

### **Step 6: Tool Usage & Return**
```
User → Use Tool → Request Return → Admin Processes
```

**Return Process:**
- User requests tool return
- Admin processes return
- Tool becomes available again

---

## 🔧 **Technical Implementation**

### **Key Files & Their Functions**

#### **Frontend Files**
- `hired-tools.php` - Main hiring interface for users
- `admin-hired-tools.php` - Admin management interface
- `admin/dashboard.php` - Admin dashboard with links

#### **Backend Files**
- `php/process_hired_tool.php` - Handles all CRUD operations
- `php/get_hired_tool_details.php` - Returns tool details
- `php/config.php` - Database and site configuration

#### **Database Tables**
- `products` - Available tools for hiring
- `hired_tools` - Hired tool records
- `users` - User accounts
- `categories` - Tool categories

### **API Endpoints**

#### **Booking Operations**
- `action=book_tool` - User books a tool
- `action=return_tool` - User requests return

#### **Admin Operations**
- `action=add_hired_tool` - Admin adds tool record
- `action=edit_hired_tool` - Admin edits tool record
- `action=delete_hired_tool` - Admin deletes tool record
- `action=approve_tool` - Admin approves booking
- `action=reject_tool` - Admin rejects booking
- `action=activate_tool` - Admin activates approved tool

---

## 🛡️ **Security & Validation**

### **Access Control**
- ✅ Admin authentication for management functions
- ✅ User authentication for booking
- ✅ Guest access for viewing only

### **Data Validation**
- ✅ Date conflict prevention
- ✅ Product availability checks
- ✅ User existence validation
- ✅ Price calculation validation

### **Error Handling**
- ✅ User-friendly error messages
- ✅ Database error logging
- ✅ Graceful failure handling

---

## 🎨 **User Interface Features**

### **For Guests**
- 🔍 Search and filter tools
- 📱 Responsive design
- 💡 Clear registration prompts
- 📊 Tool information display

### **For Users**
- 📅 Date selection with validation
- 💰 Auto-price calculation
- 📋 Booking history
- 🔄 Return request functionality

### **For Admins**
- 📊 Dashboard statistics
- ⚡ Quick action buttons
- 📝 Detailed management interface
- 🔍 Advanced filtering options

---

## 📊 **Status Flow**

```
Tool Available → User Books → Pending → Admin Reviews
     ↓              ↓           ↓           ↓
Available ← Returned ← Active ← Approved ← Pending
     ↓              ↓           ↓           ↓
Available ← Returned ← Active ← Approved ← Rejected
```

### **Status Meanings**
- **Available** - Tool can be booked
- **Pending** - Booking awaiting admin approval
- **Approved** - Booking approved, ready for activation
- **Active** - Tool is currently hired
- **Returned** - Tool has been returned
- **Rejected** - Booking was rejected

---

## 🚀 **Usage Examples**

### **Example 1: New User Booking**
1. Guest visits `hired-tools.php`
2. Sees available tools and registers
3. Logs in and books a drill for 3 days
4. Admin receives notification
5. Admin approves the booking
6. User can pick up the tool

### **Example 2: Admin Direct Management**
1. Admin adds hired tool record directly
2. Sets status to "active"
3. User sees tool in their "My Hired Tools"
4. User can request return when done

### **Example 3: Conflict Prevention**
1. User books tool for specific dates
2. Another user tries to book same tool for overlapping dates
3. System prevents double-booking
4. Shows error message to second user

---

## 🔮 **Future Enhancements**

### **Planned Features**
- 📧 Email notifications for status changes
- 📅 Calendar view of tool availability
- 💳 Payment integration
- 📱 Mobile app version
- 📊 Advanced analytics and reporting

### **Potential Improvements**
- 🔔 Real-time notifications
- 📍 Location-based tool pickup
- ⭐ User reviews and ratings
- 🎯 Recommendation system

---

## 🛠️ **Troubleshooting**

### **Common Issues**
1. **Tool not showing** - Check if `is_available = 1` in products table
2. **Booking fails** - Verify user is logged in and dates are valid
3. **Admin can't approve** - Check admin permissions and tool status
4. **Price calculation wrong** - Verify daily rate and date calculation

### **Debug Steps**
1. Check browser console for JavaScript errors
2. Verify database connections
3. Check PHP error logs
4. Validate form data submission

---

## 📞 **Support**

For technical support or questions about the hired tools system:
- Check the database schema in `database/schema.sql`
- Review the admin documentation in `ADMIN_HIRED_TOOLS_README.md`
- Test the workflow using the provided examples

The system is now fully functional and ready for production use! 🎉 