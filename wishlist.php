<?php
require_once 'php/config.php';
$pageTitle = 'Wishlist';
$currentPage = 'wishlist';

// Redirect to login if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

try {
    $conn = getDBConnection();
    
    // Get user's wishlist items
    $user_id = $_SESSION['user_id'];
    $query = "SELECT w.*, p.name, p.price, p.image, p.description 
              FROM wishlist w 
              JOIN products p ON w.product_id = p.id 
              WHERE w.user_id = ? 
              ORDER BY w.created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = "An error occurred while loading your wishlist.";
}

include 'includes/header.php';
?>

<!-- Wishlist Hero Section -->
<section class="wishlist-hero">
    <div class="container">
        <div class="wishlist-hero-content">
            <h1 class="wishlist-hero-title">
                <i class="fas fa-heart me-3"></i>
                My Wishlist
            </h1>
            <p class="wishlist-hero-subtitle">
                Save your favorite products for later purchase.
            </p>
        </div>
    </div>
</section>

<!-- Wishlist Content -->
<section class="wishlist-content">
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($wishlist_items)): ?>
            <!-- Empty Wishlist State -->
            <div class="wishlist-empty">
                <div class="wishlist-empty-icon">
                    <i class="fas fa-heart-broken"></i>
                </div>
                <h3 class="wishlist-empty-title">Your Wishlist is Empty</h3>
                <p class="wishlist-empty-text">
                    Start building your wishlist by browsing our products and adding items you love.
                </p>
                <div class="wishlist-empty-actions">
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Browse Products
                    </a>
                    <a href="hired-tools.php" class="btn btn-outline-primary">
                        <i class="fas fa-wrench me-2"></i>
                        View Hired Tools
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Wishlist Items -->
            <div class="wishlist-header">
                <h2 class="wishlist-title">
                    <i class="fas fa-heart me-2"></i>
                    Your Wishlist (<?php echo count($wishlist_items); ?> items)
                </h2>
                <div class="wishlist-actions">
                    <button class="btn btn-outline-danger" onclick="clearWishlist()">
                        <i class="fas fa-trash me-2"></i>
                        Clear All
                    </button>
                </div>
            </div>
            
            <div class="wishlist-grid">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-item" data-product-id="<?php echo $item['product_id']; ?>">
                        <div class="wishlist-item-image">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 class="wishlist-product-img">
                        </div>
                        
                        <div class="wishlist-item-content">
                            <h3 class="wishlist-item-title">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </h3>
                            
                            <p class="wishlist-item-description">
                                <?php echo htmlspecialchars(substr($item['description'], 0, 100)) . '...'; ?>
                            </p>
                            
                            <div class="wishlist-item-price">
                                <span class="price-amount">$<?php echo number_format($item['price'], 2); ?></span>
                            </div>
                            
                            <div class="wishlist-item-actions">
                                <button class="btn btn-primary btn-sm" onclick="addToCart(<?php echo $item['product_id']; ?>)">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    Add to Cart
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="removeFromWishlist(<?php echo $item['product_id']; ?>)">
                                    <i class="fas fa-heart-broken me-1"></i>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Add Wishlist CSS -->
<style>
/* Wishlist Styles */
.wishlist-hero {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    padding: 3rem 0 2rem;
    color: white;
    text-align: center;
}

.wishlist-hero-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.wishlist-hero-subtitle {
    font-size: 1.125rem;
    opacity: 0.9;
}

.wishlist-content {
    padding: 3rem 0;
    background: #f8f9fa;
}

.wishlist-empty {
    text-align: center;
    padding: 4rem 0;
}

.wishlist-empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.wishlist-empty-title {
    font-size: 1.5rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.wishlist-empty-text {
    color: #6c757d;
    margin-bottom: 2rem;
}

.wishlist-empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.wishlist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wishlist-title {
    margin: 0;
    color: #495057;
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.wishlist-item {
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.wishlist-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.wishlist-item-image {
    height: 200px;
    overflow: hidden;
}

.wishlist-product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist-item:hover .wishlist-product-img {
    transform: scale(1.05);
}

.wishlist-item-content {
    padding: 1.5rem;
}

.wishlist-item-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.wishlist-item-description {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.wishlist-item-price {
    margin-bottom: 1rem;
}

.price-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
}

.wishlist-item-actions {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .wishlist-hero-title {
        font-size: 2rem;
    }
    
    .wishlist-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .wishlist-grid {
        grid-template-columns: 1fr;
    }
    
    .wishlist-empty-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<!-- Wishlist JavaScript -->
<script>
function addToCart(productId) {
    const formData = new FormData();
    formData.append('action', 'add_to_cart');
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    
    fetch('php/process_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart successfully!', 'success');
            // Optionally remove from wishlist after adding to cart
            removeFromWishlist(productId);
        } else {
            showNotification('Error: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

function removeFromWishlist(productId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        const formData = new FormData();
        formData.append('action', 'remove_from_wishlist');
        formData.append('product_id', productId);
        
        fetch('php/process_wishlist.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Item removed from wishlist', 'success');
                // Remove the item from the DOM
                const itemElement = document.querySelector(`[data-product-id="${productId}"]`);
                if (itemElement) {
                    itemElement.remove();
                    // Update wishlist count
                    updateWishlistCount();
                }
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

function clearWishlist() {
    if (confirm('Are you sure you want to clear your entire wishlist? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('action', 'clear_wishlist');
        
        fetch('php/process_wishlist.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Wishlist cleared successfully', 'success');
                // Reload page to show empty state
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

function updateWishlistCount() {
    const items = document.querySelectorAll('.wishlist-item');
    const count = items.length;
    
    // Update the title
    const title = document.querySelector('.wishlist-title');
    if (title) {
        title.innerHTML = `<i class="fas fa-heart me-2"></i>Your Wishlist (${count} items)`;
    }
    
    // If no items left, reload to show empty state
    if (count === 0) {
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
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

<?php include 'includes/footer.php'; ?> 