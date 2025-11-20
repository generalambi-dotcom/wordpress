/**
 * Wishlist Functionality - Vanilla JavaScript
 * Handles wishlist with localStorage for guests and REST API sync for logged-in users
 */

(function() {
    'use strict';

    const WISHLIST_KEY = 'ls_wishlist';
    const wishlistState = {
        items: [],
        isLoggedIn: false
    };

    /**
     * Initialize wishlist on page load
     */
    function initWishlist() {
        // Check if user is logged in
        wishlistState.isLoggedIn = lyststyleData.isUserLoggedIn || false;

        // Load wishlist from localStorage
        loadWishlistFromStorage();

        // Sync with server if logged in
        if (wishlistState.isLoggedIn) {
            syncWishlistWithServer();
        }

        // Mark heart icons as active based on wishlist
        updateWishlistUI();

        // Attach event listeners to wishlist buttons
        attachWishlistListeners();

        // Update wishlist count in header
        updateWishlistCount();

        // Load wishlist page if we're on it
        if (document.querySelector('.page-wishlist')) {
            loadWishlistPage();
        }
    }

    /**
     * Load wishlist from localStorage
     */
    function loadWishlistFromStorage() {
        try {
            const stored = localStorage.getItem(WISHLIST_KEY);
            if (stored) {
                wishlistState.items = JSON.parse(stored);
            }
        } catch (error) {
            console.error('Error loading wishlist from localStorage:', error);
            wishlistState.items = [];
        }
    }

    /**
     * Save wishlist to localStorage
     */
    function saveWishlistToStorage() {
        try {
            localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlistState.items));
        } catch (error) {
            console.error('Error saving wishlist to localStorage:', error);
        }
    }

    /**
     * Sync localStorage wishlist with server for logged-in users
     */
    function syncWishlistWithServer() {
        if (!wishlistState.isLoggedIn) {
            return;
        }

        // Get server wishlist
        fetch(lyststyleData.restUrl + 'lyststyle/v1/wishlist/get', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': lyststyleData.restNonce
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.wishlist) {
                // Merge local and server wishlists
                const serverWishlist = data.wishlist.map(id => parseInt(id));
                const localWishlist = wishlistState.items;

                // Combine and deduplicate
                const mergedWishlist = [...new Set([...serverWishlist, ...localWishlist])];

                // Update state and localStorage
                wishlistState.items = mergedWishlist;
                saveWishlistToStorage();

                // Sync any local-only items to server
                localWishlist.forEach(productId => {
                    if (!serverWishlist.includes(productId)) {
                        syncToServer(productId, 'add');
                    }
                });

                updateWishlistUI();
                updateWishlistCount();
            }
        })
        .catch(error => {
            console.error('Error syncing wishlist with server:', error);
        });
    }

    /**
     * Update wishlist UI - mark heart icons as active
     */
    function updateWishlistUI() {
        const wishlistButtons = document.querySelectorAll('.wishlist-btn, .wishlist-btn-large');

        wishlistButtons.forEach(button => {
            const productId = parseInt(button.dataset.productId);
            const isInWishlist = wishlistState.items.includes(productId);

            if (isInWishlist) {
                button.classList.add('active');
                button.setAttribute('aria-label', 'Remove from wishlist');
                const btnText = button.querySelector('.btn-text');
                if (btnText) {
                    btnText.textContent = 'Remove from Wishlist';
                }
            } else {
                button.classList.remove('active');
                button.setAttribute('aria-label', 'Add to wishlist');
                const btnText = button.querySelector('.btn-text');
                if (btnText) {
                    btnText.textContent = 'Add to Wishlist';
                }
            }
        });
    }

    /**
     * Attach event listeners to wishlist buttons
     */
    function attachWishlistListeners() {
        // Use event delegation for dynamically added buttons
        document.addEventListener('click', function(e) {
            const wishlistBtn = e.target.closest('.wishlist-btn, .wishlist-btn-large');
            if (!wishlistBtn) return;

            e.preventDefault();
            e.stopPropagation();

            const productId = parseInt(wishlistBtn.dataset.productId);
            const isActive = wishlistBtn.classList.contains('active');

            if (isActive) {
                removeFromWishlist(productId);
            } else {
                addToWishlist(productId);
            }
        });
    }

    /**
     * Add product to wishlist
     */
    function addToWishlist(productId) {
        if (!productId) return;

        // Add to local state
        if (!wishlistState.items.includes(productId)) {
            wishlistState.items.push(productId);
            saveWishlistToStorage();
        }

        // Sync to server if logged in
        if (wishlistState.isLoggedIn) {
            syncToServer(productId, 'add');
        }

        // Update UI
        updateWishlistUI();
        updateWishlistCount();
        showNotification('Added to wishlist');
    }

    /**
     * Remove product from wishlist
     */
    function removeFromWishlist(productId) {
        if (!productId) return;

        // Remove from local state
        wishlistState.items = wishlistState.items.filter(id => id !== productId);
        saveWishlistToStorage();

        // Sync to server if logged in
        if (wishlistState.isLoggedIn) {
            syncToServer(productId, 'remove');
        }

        // Update UI
        updateWishlistUI();
        updateWishlistCount();
        showNotification('Removed from wishlist');

        // If on wishlist page, reload products
        if (document.querySelector('.page-wishlist')) {
            setTimeout(() => loadWishlistPage(), 300);
        }
    }

    /**
     * Sync wishlist action to server
     */
    function syncToServer(productId, action) {
        if (!wishlistState.isLoggedIn) return;

        fetch(lyststyleData.restUrl + 'lyststyle/v1/wishlist/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': lyststyleData.restNonce
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                product_id: productId,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to sync wishlist to server:', data);
            }
        })
        .catch(error => {
            console.error('Error syncing to server:', error);
        });
    }

    /**
     * Update wishlist count in header
     */
    function updateWishlistCount() {
        const wishlistLink = document.getElementById('wishlist-link');
        if (!wishlistLink) return;

        // Remove existing count badge
        const existingBadge = wishlistLink.querySelector('.wishlist-count');
        if (existingBadge) {
            existingBadge.remove();
        }

        // Add count badge if items exist
        const count = wishlistState.items.length;
        if (count > 0) {
            const badge = document.createElement('span');
            badge.className = 'wishlist-count';
            badge.textContent = count;
            wishlistLink.appendChild(badge);
        }
    }

    /**
     * Load wishlist page products
     */
    function loadWishlistPage() {
        const wishlistContainer = document.getElementById('wishlist-products');
        if (!wishlistContainer) return;

        const loading = document.querySelector('.wishlist-loading');
        const emptyMessage = document.getElementById('wishlist-empty');

        if (wishlistState.items.length === 0) {
            if (loading) loading.style.display = 'none';
            if (emptyMessage) emptyMessage.style.display = 'block';
            return;
        }

        // Fetch product data via REST API
        fetch(lyststyleData.restUrl + 'lyststyle/v1/products/by-ids', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': lyststyleData.restNonce
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                product_ids: wishlistState.items
            })
        })
        .then(response => response.json())
        .then(data => {
            if (loading) loading.style.display = 'none';

            if (data.success && data.products && data.products.length > 0) {
                renderWishlistProducts(data.products);
                wishlistContainer.style.display = 'grid';
                if (emptyMessage) emptyMessage.style.display = 'none';
            } else {
                if (emptyMessage) emptyMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching wishlist products:', error);
            if (loading) loading.style.display = 'none';
            if (emptyMessage) emptyMessage.style.display = 'block';
        });
    }

    /**
     * Render wishlist products on the page
     */
    function renderWishlistProducts(products) {
        const container = document.getElementById('wishlist-products');
        if (!container) return;

        container.innerHTML = '';

        products.forEach(product => {
            const currencySymbol = product.currency === 'GBP' ? '£' :
                                  product.currency === 'USD' ? '$' : '€';

            const priceDisplay = product.price > 0 ?
                `<div class="product-price">
                    <span class="price-from">FROM</span>
                    <span class="price-amount">${currencySymbol}${parseFloat(product.price).toFixed(2)}</span>
                </div>` : '';

            const imageDisplay = product.image ?
                `<img src="${product.image}" alt="${escapeHtml(product.title)}" loading="lazy">` :
                '<div class="product-placeholder"><span>No image</span></div>';

            const brandDisplay = product.brand ?
                `<div class="brand">${escapeHtml(product.brand)}</div>` : '';

            const productCard = `
                <article class="product-card" data-product-id="${product.id}">
                    <div class="product-card-inner">
                        <div class="product-image">
                            <a href="${product.permalink}">
                                ${imageDisplay}
                            </a>
                            <button class="wishlist-btn active" data-product-id="${product.id}" aria-label="Remove from wishlist">
                                <span class="icon-heart">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </span>
                                <span class="icon-heart-filled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div class="product-details">
                            ${brandDisplay}
                            <h3 class="product-title"><a href="${product.permalink}">${escapeHtml(product.title)}</a></h3>
                            ${priceDisplay}
                        </div>
                    </div>
                </article>
            `;

            container.insertAdjacentHTML('beforeend', productCard);
        });
    }

    /**
     * Show notification message
     */
    function showNotification(message) {
        // Remove existing notifications
        const existingNotification = document.querySelector('.wishlist-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'wishlist-notification';
        notification.textContent = message;
        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Add necessary styles
     */
    function addStyles() {
        if (document.getElementById('wishlist-styles')) return;

        const style = document.createElement('style');
        style.id = 'wishlist-styles';
        style.textContent = `
            .wishlist-notification {
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: #000;
                color: #fff;
                padding: 15px 25px;
                border-radius: 4px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                font-size: 14px;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            }

            .wishlist-notification.fade-out {
                animation: fadeOut 0.3s ease;
                opacity: 0;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes fadeOut {
                from {
                    opacity: 1;
                }
                to {
                    opacity: 0;
                }
            }

            .wishlist-count {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 18px;
                height: 18px;
                padding: 0 5px;
                background: #FF6B6B;
                color: #fff;
                border-radius: 50%;
                font-size: 11px;
                font-weight: 600;
                position: absolute;
                top: 0;
                right: 0;
                line-height: 1;
            }

            .header-action-link {
                position: relative;
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            addStyles();
            initWishlist();
        });
    } else {
        addStyles();
        initWishlist();
    }

})();
