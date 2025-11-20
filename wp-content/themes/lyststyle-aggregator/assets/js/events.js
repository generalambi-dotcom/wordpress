/**
 * Events Tracking - Vanilla JavaScript
 * Logs user events using REST API endpoint /lyststyle/v1/events/log
 */

(function() {
    'use strict';

    const eventQueue = [];
    let isProcessingQueue = false;

    /**
     * Initialize events tracking
     */
    function initEventsTracking() {
        // Track product view on single product page
        trackProductView();

        // Track affiliate link clicks
        trackAffiliateLinkClicks();

        // Track category views
        trackCategoryView();

        // Track brand views
        trackBrandView();

        // Track search events
        trackSearchEvents();

        // Process event queue periodically
        setInterval(processEventQueue, 5000);
    }

    /**
     * Log event to server via REST API
     */
    function logEvent(eventType, productId = 0, eventValue = null, meta = null) {
        const eventData = {
            event_type: eventType,
            product_id: productId,
            event_value: eventValue,
            meta: meta
        };

        // Add to queue
        eventQueue.push(eventData);

        // Process queue if not already processing
        if (!isProcessingQueue) {
            processEventQueue();
        }
    }

    /**
     * Process event queue
     */
    function processEventQueue() {
        if (isProcessingQueue || eventQueue.length === 0) {
            return;
        }

        isProcessingQueue = true;

        // Get next event from queue
        const eventData = eventQueue.shift();

        // Send to server
        fetch(lyststyleData.restUrl + 'lyststyle/v1/events/log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': lyststyleData.restNonce
            },
            credentials: 'same-origin',
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.warn('Event tracking failed:', data);
            }
        })
        .catch(error => {
            console.error('Error logging event:', error);
        })
        .finally(() => {
            isProcessingQueue = false;

            // Process next event in queue if any
            if (eventQueue.length > 0) {
                setTimeout(processEventQueue, 100);
            }
        });
    }

    /**
     * Track product view on single product page
     */
    function trackProductView() {
        // Check if we're on a single product page
        const productPage = document.querySelector('.single-product');
        if (!productPage) return;

        // Get product ID from body class or data attribute
        const productId = getProductIdFromPage();
        if (!productId) return;

        // Get product data for meta
        const productTitle = document.querySelector('.product-title, h1.entry-title');
        const productBrand = document.querySelector('.product-brand, .brand-name');
        const productPrice = document.querySelector('.product-price .price-amount');

        const meta = {
            product_title: productTitle ? productTitle.textContent.trim() : '',
            brand: productBrand ? productBrand.textContent.trim() : '',
            price: productPrice ? parseFloat(productPrice.textContent.replace(/[^0-9.]/g, '')) : null,
            page_url: window.location.href,
            referrer: document.referrer
        };

        // Log view_product event
        logEvent('view_product', productId, null, meta);
    }

    /**
     * Track affiliate link clicks
     */
    function trackAffiliateLinkClicks() {
        // Use event delegation for dynamically added links
        document.addEventListener('click', function(e) {
            // Check if clicked element is an affiliate link
            const affiliateLink = e.target.closest('.affiliate-link, .retailer-link, .buy-now-btn, .shop-now-btn');
            if (!affiliateLink) return;

            // Get product ID
            const productId = affiliateLink.dataset.productId ||
                            getProductIdFromParent(affiliateLink) ||
                            getProductIdFromPage();

            if (!productId) return;

            // Get retailer info
            const retailerName = affiliateLink.dataset.retailer ||
                               affiliateLink.textContent.trim() ||
                               'Unknown';

            const retailerUrl = affiliateLink.href || '';
            const price = affiliateLink.dataset.price || null;

            const meta = {
                retailer: retailerName,
                url: retailerUrl,
                price: price ? parseFloat(price) : null,
                link_text: affiliateLink.textContent.trim(),
                page_url: window.location.href
            };

            // Log click_out event
            logEvent('click_out', productId, price, meta);
        });
    }

    /**
     * Track category views
     */
    function trackCategoryView() {
        // Check if we're on a category archive
        const categoryPage = document.querySelector('.tax-product_category');
        if (!categoryPage) return;

        // Get category from body class
        const bodyClasses = document.body.className.split(' ');
        let categorySlug = null;

        bodyClasses.forEach(className => {
            if (className.startsWith('term-')) {
                categorySlug = className.replace('term-', '');
            }
        });

        if (!categorySlug) return;

        // Get category name
        const categoryTitle = document.querySelector('.archive-title, h1.page-title');
        const categoryName = categoryTitle ? categoryTitle.textContent.trim() : categorySlug;

        const meta = {
            category_slug: categorySlug,
            category_name: categoryName,
            page_url: window.location.href,
            referrer: document.referrer
        };

        // Log view_category event
        logEvent('view_category', 0, null, meta);
    }

    /**
     * Track brand views
     */
    function trackBrandView() {
        // Check if we're on a brand archive
        const brandPage = document.querySelector('.tax-brand');
        if (!brandPage) return;

        // Get brand from body class
        const bodyClasses = document.body.className.split(' ');
        let brandSlug = null;

        bodyClasses.forEach(className => {
            if (className.startsWith('term-')) {
                brandSlug = className.replace('term-', '');
            }
        });

        if (!brandSlug) return;

        // Get brand name
        const brandTitle = document.querySelector('.archive-title, h1.page-title');
        const brandName = brandTitle ? brandTitle.textContent.trim() : brandSlug;

        const meta = {
            brand_slug: brandSlug,
            brand_name: brandName,
            page_url: window.location.href,
            referrer: document.referrer
        };

        // Log view_brand event
        logEvent('view_brand', 0, null, meta);
    }

    /**
     * Track search events
     */
    function trackSearchEvents() {
        // Track search form submission
        const searchForms = document.querySelectorAll('.search-form, form[role="search"]');

        searchForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const searchInput = form.querySelector('input[type="search"], input[name="s"]');
                if (!searchInput) return;

                const searchQuery = searchInput.value.trim();
                if (!searchQuery) return;

                const meta = {
                    search_query: searchQuery,
                    search_location: 'header',
                    page_url: window.location.href
                };

                // Log search event
                logEvent('search', 0, null, meta);
            });
        });

        // Track if we're on a search results page
        const searchResultsPage = document.querySelector('.search-results');
        if (searchResultsPage) {
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('s');

            if (searchQuery) {
                const resultsCount = document.querySelectorAll('.product-card, .search-result').length;

                const meta = {
                    search_query: searchQuery,
                    results_count: resultsCount,
                    page_url: window.location.href,
                    referrer: document.referrer
                };

                logEvent('view_search_results', 0, resultsCount, meta);
            }
        }
    }

    /**
     * Get product ID from page
     */
    function getProductIdFromPage() {
        // Try to get from body class
        const bodyClasses = document.body.className.split(' ');
        for (let i = 0; i < bodyClasses.length; i++) {
            if (bodyClasses[i].startsWith('postid-')) {
                return parseInt(bodyClasses[i].replace('postid-', ''));
            }
        }

        // Try to get from article element
        const article = document.querySelector('article[data-product-id]');
        if (article) {
            return parseInt(article.dataset.productId);
        }

        // Try to get from product wrapper
        const productWrapper = document.querySelector('[data-product-id]');
        if (productWrapper) {
            return parseInt(productWrapper.dataset.productId);
        }

        return null;
    }

    /**
     * Get product ID from parent element
     */
    function getProductIdFromParent(element) {
        const productCard = element.closest('.product-card, [data-product-id]');
        if (productCard && productCard.dataset.productId) {
            return parseInt(productCard.dataset.productId);
        }
        return null;
    }

    /**
     * Track custom event (exposed globally)
     */
    window.lyststyleTrackEvent = function(eventType, productId = 0, eventValue = null, meta = null) {
        logEvent(eventType, productId, eventValue, meta);
    };

    /**
     * Track page visibility for engagement time
     */
    function trackPageEngagement() {
        let pageLoadTime = Date.now();
        let isPageVisible = !document.hidden;
        let totalEngagementTime = 0;
        let lastVisibilityChange = Date.now();

        // Track visibility changes
        document.addEventListener('visibilitychange', function() {
            const now = Date.now();

            if (document.hidden) {
                // Page became hidden
                if (isPageVisible) {
                    totalEngagementTime += (now - lastVisibilityChange);
                }
                isPageVisible = false;
            } else {
                // Page became visible
                isPageVisible = true;
                lastVisibilityChange = now;
            }
        });

        // Track engagement on page unload
        window.addEventListener('beforeunload', function() {
            if (isPageVisible) {
                totalEngagementTime += (Date.now() - lastVisibilityChange);
            }

            // Only track if engagement time is meaningful (> 3 seconds)
            if (totalEngagementTime > 3000) {
                const productId = getProductIdFromPage();
                const engagementSeconds = Math.round(totalEngagementTime / 1000);

                const meta = {
                    engagement_time: engagementSeconds,
                    page_url: window.location.href
                };

                // Use beacon API for reliable tracking on unload
                if (navigator.sendBeacon) {
                    const eventData = {
                        event_type: 'page_engagement',
                        product_id: productId || 0,
                        event_value: engagementSeconds,
                        meta: meta
                    };

                    const blob = new Blob([JSON.stringify(eventData)], {
                        type: 'application/json'
                    });

                    navigator.sendBeacon(
                        lyststyleData.restUrl + 'lyststyle/v1/events/log',
                        blob
                    );
                } else {
                    // Fallback for browsers without sendBeacon
                    logEvent('page_engagement', productId || 0, engagementSeconds, meta);
                }
            }
        });
    }

    /**
     * Track scroll depth
     */
    function trackScrollDepth() {
        const scrollMilestones = [25, 50, 75, 100];
        const reachedMilestones = [];

        window.addEventListener('scroll', debounce(function() {
            const scrollPercent = Math.round(
                (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
            );

            scrollMilestones.forEach(milestone => {
                if (scrollPercent >= milestone && !reachedMilestones.includes(milestone)) {
                    reachedMilestones.push(milestone);

                    const productId = getProductIdFromPage();
                    const meta = {
                        scroll_depth: milestone,
                        page_url: window.location.href
                    };

                    logEvent('scroll_depth', productId || 0, milestone, meta);
                }
            });
        }, 500));
    }

    /**
     * Debounce utility function
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initEventsTracking();
            trackPageEngagement();
            trackScrollDepth();
        });
    } else {
        initEventsTracking();
        trackPageEngagement();
        trackScrollDepth();
    }

})();
