<?php
require_once '../php/config.php';
$pageTitle = 'Admin - Manage Users';
$currentPage = 'admin-users';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

try {
    $conn = getDBConnection();
    
    // Get filter parameters
    $role_filter = $_GET['role'] ?? '';
    $search_query = $_GET['search'] ?? '';
    
    // Build query with filters
    $where_conditions = [];
    $params = [];
    
    if ($role_filter) {
        $where_conditions[] = "role = ?";
        $params[] = $role_filter;
    }
    
    if ($search_query) {
        $where_conditions[] = "(username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
        $params[] = "%$search_query%";
        $params[] = "%$search_query%";
        $params[] = "%$search_query%";
        $params[] = "%$search_query%";
    }
    
    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
    
    $query = "SELECT * FROM users $where_clause ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stats_query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
                        SUM(CASE WHEN role = 'customer' THEN 1 ELSE 0 END) as customers
                    FROM users";
    $stats_stmt = $conn->prepare($stats_query);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch();
    
} catch (Exception $e) {
    $error = "An error occurred while fetching users.";
}

include '../includes/header.php';
?>

<!-- Users Management Hero Section -->
<section class="users-hero">
    <div class="container">
        <div class="users-hero-content">
            <h1 class="users-hero-title">
                <i class="fas fa-users me-3"></i>
                Manage Users
            </h1>
            <p class="users-hero-subtitle">
                Manage your user accounts, roles, and permissions with ease.
            </p>
            <div class="users-hero-actions">
                <button class="users-btn users-btn-success users-interactive" onclick="showAddUserModal()">
                    <i class="fas fa-plus"></i>
                    Add New User
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Users Management Content -->
<section class="users-management">
    <div class="users-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger users-interactive">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="users-stats-grid">
            <div class="users-stat-card total users-interactive">
                <div class="users-stat-content">
                    <div class="users-stat-info">
                        <h3><?php echo number_format($stats['total'] ?? 0); ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="users-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="users-stat-card admins users-interactive">
                <div class="users-stat-content">
                    <div class="users-stat-info">
                        <h3><?php echo number_format($stats['admins'] ?? 0); ?></h3>
                        <p>Administrators</p>
                    </div>
                    <div class="users-stat-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
            
            <div class="users-stat-card customers users-interactive">
                <div class="users-stat-content">
                    <div class="users-stat-info">
                        <h3><?php echo number_format($stats['customers'] ?? 0); ?></h3>
                        <p>Customers</p>
                    </div>
                    <div class="users-stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="users-filters-card">
            <div class="users-filters-header">
                <i class="fas fa-filter"></i>
                <h5>Search & Filter Users</h5>
            </div>
            <div class="users-filters-body">
                <form method="GET" class="users-filters-form">
                    <div class="users-form-group">
                        <label for="search" class="users-form-label">Search Users</label>
                        <input type="text" class="users-form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search_query); ?>" 
                               placeholder="Search by name, email, or username...">
                    </div>
                    
                    <div class="users-form-group">
                        <label for="role" class="users-form-label">Role Filter</label>
                        <select class="users-form-control" id="role" name="role">
                            <option value="">All Roles</option>
                            <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="customer" <?php echo $role_filter === 'customer' ? 'selected' : ''; ?>>Customer</option>
                        </select>
                    </div>
                    
                    <div class="users-form-group">
                        <button type="submit" class="users-btn users-btn-primary">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>
                        <a href="manage-users.php" class="users-btn users-btn-secondary">
                            <i class="fas fa-times"></i>
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="users-table-card">
            <div class="users-table-header">
                <div>
                    <i class="fas fa-table me-2"></i>
                    <h5>User Management</h5>
                </div>
                <div>
                    <span class="badge bg-light text-dark"><?php echo count($users); ?> users</span>
                </div>
            </div>
            <div class="users-table-body">
                <?php if (empty($users)): ?>
                    <div class="users-empty-state">
                        <div class="users-empty-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="users-empty-title">No Users Found</h3>
                        <p class="users-empty-text">No users match the current filters</p>
                        <button class="users-btn users-btn-success users-interactive" onclick="showAddUserModal()">
                            <i class="fas fa-plus"></i>
                            Add First User
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr class="users-interactive">
                                        <td><strong>#<?php echo $user['id']; ?></strong></td>
                                        <td>
                                            <div class="users-user-info">
                                                <div class="users-user-name">
                                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                                </div>
                                                <div class="users-user-username">
                                                    @<?php echo htmlspecialchars($user['username']); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="users-badge <?php echo $user['role']; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="users-actions">
                                                <button class="users-action-btn edit users-interactive" 
                                                        onclick="editUser(<?php echo $user['id']; ?>)"
                                                        title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                    <button class="users-action-btn delete users-interactive" 
                                                            onclick="deleteUser(<?php echo $user['id']; ?>)"
                                                            title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit User Modal -->
<div class="modal fade users-modal" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="user_id">
                    <input type="hidden" id="formAction" name="action" value="add_user">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text">Leave blank when editing to keep current password</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="users-btn users-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="users-btn users-btn-primary users-interactive" onclick="submitUserForm()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add the manage users CSS -->
<link rel="stylesheet" href="../css/manage-users.css">

<!-- JavaScript for enhanced user management interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation class
    const management = document.querySelector('.users-management');
    if (management) {
        management.classList.add('users-loading');
        setTimeout(() => {
            management.classList.add('loaded');
        }, 100);
    }
    
    // Add hover effects for interactive elements
    const interactiveElements = document.querySelectorAll('.users-interactive');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Enhanced stat card animations
    const statCards = document.querySelectorAll('.users-stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.users-stat-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.users-stat-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Table row hover effects
    const tableRows = document.querySelectorAll('.users-table tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--users-bg-light)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Action button hover effects
    const actionButtons = document.querySelectorAll('.users-action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Form control focus effects
    const formControls = document.querySelectorAll('.users-form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.borderColor = 'var(--users-primary)';
            this.style.boxShadow = '0 0 0 3px rgba(99, 102, 241, 0.1)';
        });
        
        control.addEventListener('blur', function() {
            this.style.borderColor = 'var(--users-border)';
            this.style.boxShadow = 'none';
        });
    });
});

function showAddUserModal() {
    document.getElementById('userModalTitle').textContent = 'Add New User';
    document.getElementById('formAction').value = 'add_user';
    document.getElementById('userId').value = '';
    document.getElementById('userForm').reset();
    document.getElementById('password').required = true;
    document.getElementById('confirmPassword').required = true;
    
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

function editUser(userId) {
    // Load user data for editing
    fetch(`../php/get_user_details.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data;
                
                document.getElementById('userModalTitle').textContent = 'Edit User';
                document.getElementById('formAction').value = 'edit_user';
                document.getElementById('userId').value = user.id;
                document.getElementById('username').value = user.username;
                document.getElementById('email').value = user.email;
                document.getElementById('firstName').value = user.first_name;
                document.getElementById('lastName').value = user.last_name;
                document.getElementById('phone').value = user.phone || '';
                document.getElementById('role').value = user.role;
                document.getElementById('address').value = user.address || '';
                
                // Make password optional for editing
                document.getElementById('password').required = false;
                document.getElementById('confirmPassword').required = false;
                
                new bootstrap.Modal(document.getElementById('userModal')).show();
            } else {
                showNotification('Error: ' + data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading user data', 'error');
        });
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('user_id', userId);
        
        fetch('../php/process_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification('Error: ' + data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }
}

function submitUserForm() {
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    
    // Validate password match
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');
    
    if (password && password !== confirmPassword) {
        showNotification('Passwords do not match', 'error');
        return;
    }
    
    // If editing and no password provided, remove password fields
    if (formData.get('action') === 'edit_user' && !password) {
        formData.delete('password');
        formData.delete('confirm_password');
    }
    
    fetch('../php/process_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification('Error: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInFromRight 0.3s ease;
    `;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutToRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInFromRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutToRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php include '../includes/footer.php'; ?> 