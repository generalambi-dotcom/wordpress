/**
 * Product Filters - Vanilla JavaScript
 * Handles filter form submission, price validation, and clear filters
 */

(function() {
    'use strict';

    /**
     * Initialize filters on page load
     */
    function initFilters() {
        const filtersForm = document.querySelector('.filters-form');
        if (!filtersForm) return;

        // Handle form submission
        filtersForm.addEventListener('submit', handleFilterSubmit);

        // Price range validation
        setupPriceValidation();

        // Clear filters button
        const clearButton = document.querySelector('.btn-clear-filters');
        if (clearButton) {
            clearButton.addEventListener('click', handleClearFilters);
        }

        // Optional: Auto-submit on select change
        // Uncomment the following to enable auto-submit on filter change
        // setupAutoSubmit(filtersForm);

        // Apply filters button (if exists)
        const applyButton = document.querySelector('.btn-apply-filters');
        if (applyButton) {
            applyButton.addEventListener('click', function(e) {
                e.preventDefault();
                filtersForm.submit();
            });
        }
    }

    /**
     * Handle filter form submission
     */
    function handleFilterSubmit(e) {
        // Get the form
        const form = e.target;

        // Remove empty values before submitting to keep URL clean
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.value === '' || input.value === null) {
                input.disabled = true;
            }
        });

        // Form will submit normally
        // Re-enable inputs after a short delay so they work for next submission
        setTimeout(() => {
            inputs.forEach(input => {
                input.disabled = false;
            });
        }, 100);
    }

    /**
     * Setup price range validation
     */
    function setupPriceValidation() {
        const minPriceInput = document.getElementById('filter-min-price');
        const maxPriceInput = document.getElementById('filter-max-price');

        if (!minPriceInput || !maxPriceInput) return;

        // Validate min price
        minPriceInput.addEventListener('change', function() {
            const minPrice = parseFloat(this.value);
            const maxPrice = parseFloat(maxPriceInput.value);

            if (!isNaN(minPrice) && minPrice < 0) {
                this.value = 0;
            }

            if (!isNaN(maxPrice) && !isNaN(minPrice) && minPrice > maxPrice) {
                this.value = maxPrice;
                showPriceError('Minimum price cannot be greater than maximum price');
            }
        });

        // Validate max price
        maxPriceInput.addEventListener('change', function() {
            const minPrice = parseFloat(minPriceInput.value);
            const maxPrice = parseFloat(this.value);

            if (!isNaN(maxPrice) && maxPrice < 0) {
                this.value = 0;
            }

            if (!isNaN(minPrice) && !isNaN(maxPrice) && maxPrice < minPrice) {
                this.value = minPrice;
                showPriceError('Maximum price cannot be less than minimum price');
            }
        });

        // Allow only numbers and decimal point
        [minPriceInput, maxPriceInput].forEach(input => {
            input.addEventListener('input', function(e) {
                // Remove any non-numeric characters except decimal point
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Ensure only one decimal point
                const parts = this.value.split('.');
                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }
            });
        });
    }

    /**
     * Show price validation error
     */
    function showPriceError(message) {
        // Remove existing error
        const existingError = document.querySelector('.price-validation-error');
        if (existingError) {
            existingError.remove();
        }

        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'price-validation-error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            padding: 5px 10px;
            background: #fff3cd;
            border-radius: 3px;
            border: 1px solid #ffc107;
        `;

        // Insert after price inputs
        const priceContainer = document.querySelector('.filter-price-range') ||
                              document.getElementById('filter-max-price').parentElement;
        if (priceContainer) {
            priceContainer.appendChild(errorDiv);

            // Remove after 3 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        }
    }

    /**
     * Handle clear filters button click
     */
    function handleClearFilters(e) {
        e.preventDefault();

        const form = document.querySelector('.filters-form');
        if (!form) return;

        // Reset the form
        form.reset();

        // Get current URL without query parameters
        const url = window.location.pathname;

        // Redirect to clean URL
        window.location.href = url;
    }

    /**
     * Setup auto-submit on filter change (optional)
     */
    function setupAutoSubmit(form) {
        const selects = form.querySelectorAll('select');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        const radios = form.querySelectorAll('input[type="radio"]');

        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                form.submit();
            });
        });

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                form.submit();
            });
        });
    }

    /**
     * Build query string from form (utility function)
     */
    function buildQueryString(form) {
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value && value !== '') {
                params.append(key, value);
            }
        }

        return params.toString();
    }

    /**
     * AJAX filtering (optional enhancement)
     * Filters products without page reload
     */
    function setupAjaxFiltering(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const queryString = buildQueryString(form);
            const url = window.location.pathname + '?' + queryString;

            // Update URL without reload
            window.history.pushState({}, '', url);

            // Fetch filtered results
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update product grid
                const newProductGrid = doc.querySelector('.product-grid');
                const currentProductGrid = document.querySelector('.product-grid');

                if (newProductGrid && currentProductGrid) {
                    currentProductGrid.innerHTML = newProductGrid.innerHTML;

                    // Scroll to results
                    currentProductGrid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Show feedback
                    showFilteredMessage();
                }
            })
            .catch(error => {
                console.error('Error filtering products:', error);
                // Fallback to normal form submission
                form.submit();
            });
        });
    }

    /**
     * Show filtered results message
     */
    function showFilteredMessage() {
        const message = document.createElement('div');
        message.className = 'filter-success-message';
        message.textContent = 'Filters applied';
        message.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 9999;
            font-size: 14px;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(message);

        setTimeout(() => {
            message.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => message.remove(), 300);
        }, 2000);
    }

    /**
     * Update URL with current filters (utility function)
     */
    function updateURLWithFilters() {
        const form = document.querySelector('.filters-form');
        if (!form) return;

        const queryString = buildQueryString(form);
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '');

        window.history.pushState({}, '', newURL);
    }

    /**
     * Get active filters count
     */
    function getActiveFiltersCount() {
        const form = document.querySelector('.filters-form');
        if (!form) return 0;

        let count = 0;
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                if (input.checked) count++;
            } else if (input.value && input.value !== '') {
                count++;
            }
        });

        return count;
    }

    /**
     * Display active filters count badge
     */
    function updateActiveFiltersDisplay() {
        const count = getActiveFiltersCount();
        const filterToggle = document.querySelector('.filters-toggle');

        if (!filterToggle) return;

        // Remove existing badge
        const existingBadge = filterToggle.querySelector('.active-filters-badge');
        if (existingBadge) {
            existingBadge.remove();
        }

        // Add badge if filters are active
        if (count > 0) {
            const badge = document.createElement('span');
            badge.className = 'active-filters-badge';
            badge.textContent = count;
            badge.style.cssText = `
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 20px;
                height: 20px;
                padding: 0 6px;
                background: #FF6B6B;
                color: white;
                border-radius: 50%;
                font-size: 11px;
                font-weight: 600;
                margin-left: 8px;
            `;
            filterToggle.appendChild(badge);
        }
    }

    /**
     * Add CSS animations
     */
    function addStyles() {
        if (document.getElementById('filters-styles')) return;

        const style = document.createElement('style');
        style.id = 'filters-styles';
        style.textContent = `
            @keyframes slideInRight {
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

            .filters-form input[type="number"]::-webkit-inner-spin-button,
            .filters-form input[type="number"]::-webkit-outer-spin-button {
                opacity: 1;
            }

            .filters-form input:invalid {
                border-color: #dc3545;
            }

            .filters-form input:focus {
                outline: 2px solid #0066cc;
                outline-offset: 2px;
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            addStyles();
            initFilters();
            updateActiveFiltersDisplay();
        });
    } else {
        addStyles();
        initFilters();
        updateActiveFiltersDisplay();
    }

    // Update filters display when form changes
    document.addEventListener('change', function(e) {
        if (e.target.closest('.filters-form')) {
            updateActiveFiltersDisplay();
        }
    });

})();
