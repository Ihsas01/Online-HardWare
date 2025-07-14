// Sample product data (in a real application, this would come from a database)
const products = [
    {
        id: 1,
        name: "Professional Hammer",
        category: "Hand Tools",
        price: 29.99,
        image: "images/products/hammer.jpg",
        description: "Professional grade hammer with ergonomic handle"
    },
    {
        id: 2,
        name: "Cordless Drill",
        category: "Power Tools",
        price: 89.99,
        image: "images/products/drill.jpg",
        description: "20V cordless drill with lithium-ion battery"
    },
    {
        id: 3,
        name: "Garden Shovel",
        category: "Garden Tools",
        price: 24.99,
        image: "images/products/shovel.jpg",
        description: "Sturdy garden shovel with wooden handle"
    },
    {
        id: 4,
        name: "Safety Goggles",
        category: "Safety Equipment",
        price: 14.99,
        image: "images/products/goggles.jpg",
        description: "Clear safety goggles with UV protection"
    }
];

// Function to create product cards
function createProductCard(product) {
    return `
        <div class="col-md-3 mb-4">
            <div class="card product-card fade-in">
                <img src="${product.image}" class="card-img-top" alt="${product.name}">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.description}</p>
                    <p class="price">$${product.price.toFixed(2)}</p>
                    <button class="btn btn-primary" onclick="addToCart(${product.id})">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Function to load featured products
function loadFeaturedProducts() {
    const featuredProductsContainer = document.getElementById('featured-products');
    if (featuredProductsContainer) {
        const featuredProductsHTML = products
            .slice(0, 4) // Show only first 4 products
            .map(product => createProductCard(product))
            .join('');
        featuredProductsContainer.innerHTML = featuredProductsHTML;
    }
}

// Function to handle search
function handleSearch(event) {
    event.preventDefault();
    const searchTerm = event.target.querySelector('input').value.toLowerCase();
    const filteredProducts = products.filter(product => 
        product.name.toLowerCase().includes(searchTerm) ||
        product.category.toLowerCase().includes(searchTerm) ||
        product.description.toLowerCase().includes(searchTerm)
    );
    
    const productsContainer = document.getElementById('featured-products');
    if (productsContainer) {
        const productsHTML = filteredProducts
            .map(product => createProductCard(product))
            .join('');
        productsContainer.innerHTML = productsHTML;
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
    // Load featured products
    loadFeaturedProducts();

    // Add search form handler
    const searchForm = document.querySelector('form.d-flex');
    if (searchForm) {
        searchForm.addEventListener('submit', handleSearch);
    }

    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
}); 