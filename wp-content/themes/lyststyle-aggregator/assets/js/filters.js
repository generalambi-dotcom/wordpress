/**
 * Product Filters JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initFilters();
    });

    /**
     * Initialize Product Filters
     */
    function initFilters() {
        const filtersForm = $('.filters-form');

        if (!filtersForm.length) {
            return;
        }

        // Auto-submit on select change (optional)
        filtersForm.find('select').on('change', function() {
            // Uncomment to enable auto-submit on filter change
            // filtersForm.submit();
        });

        // Handle filter form submission
        filtersForm.on('submit', function(e) {
            // Remove empty values before submitting
            $(this).find('input, select').each(function() {
                if ($(this).val() === '' || $(this).val() === null) {
                    $(this).prop('disabled', true);
                }
            });
        });

        // Clear filters button
        $('.btn-clear-filters').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            form[0].reset();

            // Remove query parameters from URL
            const url = window.location.pathname;
            window.history.pushState({}, '', url);
            window.location.href = url;
        });

        // Price range validation
        const minPriceInput = $('#filter-min-price');
        const maxPriceInput = $('#filter-max-price');

        minPriceInput.on('change', function() {
            const minPrice = parseInt($(this).val());
            const maxPrice = parseInt(maxPriceInput.val());

            if (maxPrice && minPrice > maxPrice) {
                $(this).val(maxPrice);
            }
        });

        maxPriceInput.on('change', function() {
            const minPrice = parseInt(minPriceInput.val());
            const maxPrice = parseInt($(this).val());

            if (minPrice && maxPrice < minPrice) {
                $(this).val(minPrice);
            }
        });
    }

    /**
     * Update URL with filter parameters
     */
    function updateURLWithFilters() {
        const form = $('.filters-form');
        const formData = form.serializeArray();
        const params = new URLSearchParams();

        formData.forEach(item => {
            if (item.value) {
                params.append(item.name, item.value);
            }
        });

        const newURL = window.location.pathname + '?' + params.toString();
        window.history.pushState({}, '', newURL);
    }

})(jQuery);
