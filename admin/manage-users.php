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

<!-- Admin User Management Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Users</h1>
            <button class="btn btn-success" onclick="showAddUserModal()">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['total'] ?? 0; ?></h5>
                        <p class="card-text text-muted">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['admins'] ?? 0; ?></h5>
                        <p class="card-text">Admins</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $stats['customers'] ?? 0; ?></h5>
                        <p class="card-text">Customers</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Users</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by name, email, or username...">
                    </div>
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role Filter</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">All Roles</option>
                            <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="customer" <?php echo $role_filter === 'customer' ? 'selected' : ''; ?>>Customer</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="manage-users.php" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h3>No Users Found</h3>
                <p class="text-muted">No users match the current filters</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
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
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong><br>
                                        <small class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-warning" 
                                                onclick="editUser(<?php echo $user['id']; ?>)"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button class="btn btn-danger" 
                                                    onclick="deleteUser(<?php echo $user['id']; ?>)"
                                                    title="Delete">
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
</section>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
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
                                <small class="text-muted">Leave blank when editing to keep current password</small>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitUserForm()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
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
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading user data');
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
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
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
        alert('Passwords do not match');
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
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<?php include '../includes/footer.php'; ?> 