/**
 * Wishlist Functionality using LocalStorage
 */

(function($) {
    'use strict';

    const WISHLIST_KEY = 'lyststyle_wishlist';

    $(document).ready(function() {
        initWishlist();
        loadWishlistPage();
    });

    /**
     * Initialize Wishlist Functionality
     */
    function initWishlist() {
        // Initialize wishlist button states
        updateWishlistButtonStates();

        // Handle wishlist button clicks
        $(document).on('click', '.wishlist-btn, .wishlist-btn-large', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = $(this).data('product-id');
            const isActive = $(this).hasClass('active');

            if (isActive) {
                removeFromWishlist(productId);
            } else {
                addToWishlist(productId);
            }

            updateWishlistButtonStates();
            updateWishlistCount();
        });

        // Update wishlist count on load
        updateWishlistCount();
    }

    /**
     * Get wishlist from localStorage
     */
    function getWishlist() {
        const wishlist = localStorage.getItem(WISHLIST_KEY);
        return wishlist ? JSON.parse(wishlist) : [];
    }

    /**
     * Save wishlist to localStorage
     */
    function saveWishlist(wishlist) {
        localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlist));
    }

    /**
     * Add product to wishlist
     */
    function addToWishlist(productId) {
        let wishlist = getWishlist();

        if (!wishlist.includes(productId)) {
            wishlist.push(productId);
            saveWishlist(wishlist);
            showNotification('Added to wishlist');
        }
    }

    /**
     * Remove product from wishlist
     */
    function removeFromWishlist(productId) {
        let wishlist = getWishlist();
        wishlist = wishlist.filter(id => id !== productId);
        saveWishlist(wishlist);
        showNotification('Removed from wishlist');
    }

    /**
     * Check if product is in wishlist
     */
    function isInWishlist(productId) {
        const wishlist = getWishlist();
        return wishlist.includes(productId);
    }

    /**
     * Update wishlist button states
     */
    function updateWishlistButtonStates() {
        $('.wishlist-btn, .wishlist-btn-large').each(function() {
            const productId = $(this).data('product-id');

            if (isInWishlist(productId)) {
                $(this).addClass('active');
                $(this).attr('aria-label', 'Remove from wishlist');
                $(this).find('.btn-text').text('Remove from Wishlist');
            } else {
                $(this).removeClass('active');
                $(this).attr('aria-label', 'Add to wishlist');
                $(this).find('.btn-text').text('Add to Wishlist');
            }
        });
    }

    /**
     * Update wishlist count in header
     */
    function updateWishlistCount() {
        const wishlist = getWishlist();
        const count = wishlist.length;
        const wishlistLink = $('#wishlist-link');

        // Remove existing count badge
        wishlistLink.find('.wishlist-count').remove();

        // Add count badge if items exist
        if (count > 0) {
            wishlistLink.append('<span class="wishlist-count">' + count + '</span>');
        }
    }

    /**
     * Load wishlist page products
     */
    function loadWishlistPage() {
        const wishlistContainer = $('#wishlist-products');

        if (!wishlistContainer.length) {
            return;
        }

        const wishlist = getWishlist();
        const loading = $('.wishlist-loading');
        const emptyMessage = $('#wishlist-empty');

        if (wishlist.length === 0) {
            loading.hide();
            emptyMessage.show();
            return;
        }

        // Fetch products via AJAX
        $.ajax({
            url: lyststyleData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'lyststyle_get_wishlist',
                nonce: lyststyleData.nonce,
                product_ids: wishlist
            },
            success: function(response) {
                loading.hide();

                if (response.success && response.data.products.length > 0) {
                    renderWishlistProducts(response.data.products);
                    wishlistContainer.show();
                } else {
                    emptyMessage.show();
                }
            },
            error: function() {
                loading.hide();
                emptyMessage.show();
            }
        });
    }

    /**
     * Render wishlist products
     */
    function renderWishlistProducts(products) {
        const container = $('#wishlist-products');
        container.empty();

        products.forEach(function(product) {
            const productCard = `
                <article class="product-card" data-product-id="${product.id}">
                    <div class="product-card-inner">
                        <div class="product-image">
                            <a href="${product.url}">
                                ${product.image ? `<img src="${product.image}" alt="${product.title}" loading="lazy">` : '<div class="product-placeholder"><span>No image</span></div>'}
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
                            ${product.brand ? `<div class="brand">${product.brand}</div>` : ''}
                            <h3 class="product-title"><a href="${product.url}">${product.title}</a></h3>
                            ${product.price > 0 ? `
                                <div class="product-price">
                                    <span class="price-from">FROM</span>
                                    <span class="price-amount">${product.currency === 'GBP' ? '£' : product.currency === 'USD' ? '$' : '€'}${parseFloat(product.price).toFixed(2)}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </article>
            `;

            container.append(productCard);
        });

        // When a product is removed from wishlist on this page, reload
        $(document).on('click', '.page-wishlist .wishlist-btn.active', function() {
            setTimeout(function() {
                loadWishlistPage();
            }, 500);
        });
    }

    /**
     * Show notification
     */
    function showNotification(message) {
        // Remove existing notifications
        $('.wishlist-notification').remove();

        // Create notification
        const notification = $('<div class="wishlist-notification">' + message + '</div>');
        $('body').append(notification);

        // Add CSS for notification
        notification.css({
            'position': 'fixed',
            'bottom': '30px',
            'right': '30px',
            'background': '#000',
            'color': '#fff',
            'padding': '15px 25px',
            'border-radius': '4px',
            'box-shadow': '0 4px 12px rgba(0,0,0,0.15)',
            'z-index': '9999',
            'animation': 'slideIn 0.3s ease',
            'font-size': '14px',
            'font-weight': '500'
        });

        // Remove after 3 seconds
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Add CSS animation
    const style = document.createElement('style');
    style.innerHTML = `
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
        }
        .header-action-link {
            position: relative;
        }
    `;
    document.head.appendChild(style);

})(jQuery);
