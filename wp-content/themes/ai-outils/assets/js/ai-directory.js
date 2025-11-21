/**
 * AI Outils Directory - AJAX Functionality
 *
 * Handles:
 * - Category filtering
 * - Load more / infinite scroll
 * - Save tool toggle
 * - Click tracking
 *
 * @package AI_Outils
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Ensure aiOutilsAjax is available
    if (typeof aiOutilsAjax === 'undefined') {
        console.warn('AI Outils: AJAX configuration not found');
        return;
    }

    const config = aiOutilsAjax;

    /**
     * ========================================================================
     * UTILITY FUNCTIONS
     * ========================================================================
     */

    /**
     * Make AJAX request
     *
     * @param {string} action AJAX action name
     * @param {object} data Additional data
     * @returns {Promise}
     */
    function ajaxRequest(action, data = {}) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', config.nonce);

        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });

        return fetch(config.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
    }

    /**
     * Debounce function
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

    /**
     * ========================================================================
     * DIRECTORY FILTER FUNCTIONALITY
     * ========================================================================
     */

    class DirectoryFilter {
        constructor(container) {
            this.container = container;
            this.grid = container.querySelector('.tools-grid, .card-grid');
            this.pagination = container.querySelector('.ajax-pagination-container');
            this.filters = container.querySelector('.directory-filters');
            this.loadMoreBtn = container.querySelector('.load-more-btn');
            this.searchInput = container.querySelector('.filter-search-input');
            this.categorySelect = container.querySelector('.filter-category-select');
            this.categoryPills = container.querySelectorAll('.filter-pill[data-category]');

            this.currentPage = 1;
            this.currentCategory = 'all';
            this.currentSearch = '';
            this.isLoading = false;

            this.init();
        }

        init() {
            // Category pills click handler
            this.categoryPills.forEach(pill => {
                pill.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.setActiveCategory(pill.dataset.category);
                    this.filterTools();
                });
            });

            // Category select dropdown
            if (this.categorySelect) {
                this.categorySelect.addEventListener('change', () => {
                    this.currentCategory = this.categorySelect.value;
                    this.filterTools();
                });
            }

            // Search input with debounce
            if (this.searchInput) {
                this.searchInput.addEventListener('input', debounce(() => {
                    this.currentSearch = this.searchInput.value;
                    this.filterTools();
                }, 300));
            }

            // Load more button
            if (this.loadMoreBtn) {
                this.loadMoreBtn.addEventListener('click', () => {
                    this.loadMore();
                });
            }

            // Pagination clicks (event delegation)
            if (this.pagination) {
                this.pagination.addEventListener('click', (e) => {
                    if (e.target.classList.contains('pagination-btn')) {
                        const page = parseInt(e.target.dataset.page, 10);
                        if (page && !isNaN(page)) {
                            this.goToPage(page);
                        }
                    }
                });
            }
        }

        setActiveCategory(category) {
            this.currentCategory = category;
            this.categoryPills.forEach(pill => {
                pill.classList.toggle('active', pill.dataset.category === category);
            });
        }

        filterTools() {
            if (this.isLoading) return;

            this.isLoading = true;
            this.currentPage = 1;
            this.showLoading();

            ajaxRequest('filter_ai_tools', {
                category: this.currentCategory,
                page: this.currentPage,
                search: this.currentSearch
            })
            .then(response => {
                if (response.success) {
                    this.grid.innerHTML = response.data.html;
                    if (this.pagination) {
                        this.pagination.innerHTML = response.data.pagination;
                    }
                    this.updateLoadMoreButton(response.data);

                    // Initialize save buttons on new content
                    initSaveButtons(this.grid);
                    initClickTracking(this.grid);
                } else {
                    this.showError(response.data?.message || config.strings.error);
                }
            })
            .catch(error => {
                console.error('Filter error:', error);
                this.showError(config.strings.error);
            })
            .finally(() => {
                this.isLoading = false;
                this.hideLoading();
            });
        }

        loadMore() {
            if (this.isLoading) return;

            this.isLoading = true;
            this.currentPage++;

            const btn = this.loadMoreBtn;
            const originalText = btn.textContent;
            btn.textContent = config.strings.loading;
            btn.disabled = true;

            ajaxRequest('load_more_ai_tools', {
                category: this.currentCategory,
                page: this.currentPage
            })
            .then(response => {
                if (response.success) {
                    // Append new content
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data.html;

                    while (temp.firstChild) {
                        this.grid.appendChild(temp.firstChild);
                    }

                    // Initialize save buttons on new content
                    initSaveButtons(this.grid);
                    initClickTracking(this.grid);

                    // Update button
                    if (response.data.has_more) {
                        btn.textContent = originalText;
                        btn.disabled = false;
                    } else {
                        btn.textContent = config.strings.noMore;
                        btn.disabled = true;
                    }
                } else {
                    btn.textContent = originalText;
                    btn.disabled = false;
                    this.currentPage--;
                }
            })
            .catch(error => {
                console.error('Load more error:', error);
                btn.textContent = originalText;
                btn.disabled = false;
                this.currentPage--;
            })
            .finally(() => {
                this.isLoading = false;
            });
        }

        goToPage(page) {
            if (this.isLoading || page === this.currentPage) return;

            this.currentPage = page;
            this.isLoading = true;
            this.showLoading();

            ajaxRequest('filter_ai_tools', {
                category: this.currentCategory,
                page: this.currentPage,
                search: this.currentSearch
            })
            .then(response => {
                if (response.success) {
                    this.grid.innerHTML = response.data.html;
                    if (this.pagination) {
                        this.pagination.innerHTML = response.data.pagination;
                    }

                    // Scroll to top of grid
                    this.grid.scrollIntoView({ behavior: 'smooth', block: 'start' });

                    // Initialize save buttons on new content
                    initSaveButtons(this.grid);
                    initClickTracking(this.grid);
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
            })
            .finally(() => {
                this.isLoading = false;
                this.hideLoading();
            });
        }

        updateLoadMoreButton(data) {
            if (!this.loadMoreBtn) return;

            if (data.page < data.max_pages) {
                this.loadMoreBtn.style.display = '';
                this.loadMoreBtn.disabled = false;
                this.loadMoreBtn.textContent = config.strings.loadMore;
            } else {
                this.loadMoreBtn.style.display = 'none';
            }
        }

        showLoading() {
            this.container.classList.add('is-loading');
        }

        hideLoading() {
            this.container.classList.remove('is-loading');
        }

        showError(message) {
            this.grid.innerHTML = `<div class="ajax-error"><p>${message}</p></div>`;
        }
    }

    /**
     * ========================================================================
     * SAVE TOOL FUNCTIONALITY
     * ========================================================================
     */

    function initSaveButtons(container = document) {
        const saveButtons = container.querySelectorAll('.save-tool-btn:not([data-initialized])');

        saveButtons.forEach(btn => {
            btn.setAttribute('data-initialized', 'true');

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const toolId = this.dataset.toolId;
                if (!toolId) return;

                // Check if logged in
                if (!config.isLoggedIn) {
                    if (confirm(config.strings.loginToSave)) {
                        window.location.href = config.loginUrl;
                    }
                    return;
                }

                // Disable button during request
                this.disabled = true;
                const originalText = this.textContent;
                this.textContent = config.strings.loading;

                ajaxRequest('toggle_tool_save', { tool_id: toolId })
                .then(response => {
                    if (response.success) {
                        const data = response.data;

                        // Update button state
                        this.classList.toggle('is-saved', data.is_saved);
                        this.textContent = data.is_saved ? config.strings.unsaveTool : config.strings.saveTool;

                        // Update save count display if present
                        const countEl = document.querySelector(`[data-save-count="${toolId}"]`);
                        if (countEl) {
                            countEl.textContent = data.save_count;
                        }

                        // Show feedback
                        showToast(data.is_saved ? config.strings.saved : config.strings.removed);
                    } else {
                        this.textContent = originalText;
                        showToast(response.data?.message || config.strings.error);
                    }
                })
                .catch(error => {
                    console.error('Save error:', error);
                    this.textContent = originalText;
                    showToast(config.strings.error);
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    }

    /**
     * Remove saved tool (for saved tools page)
     */
    function initRemoveSavedButtons(container = document) {
        const removeButtons = container.querySelectorAll('.remove-saved-btn:not([data-initialized])');

        removeButtons.forEach(btn => {
            btn.setAttribute('data-initialized', 'true');

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const toolId = this.dataset.toolId;
                const card = this.closest('.tool-card');

                if (!toolId || !card) return;

                this.disabled = true;

                ajaxRequest('remove_saved_tool_from_page', { tool_id: toolId })
                .then(response => {
                    if (response.success) {
                        // Animate removal
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';

                        setTimeout(() => {
                            card.remove();

                            // Check if empty
                            const grid = document.querySelector('.saved-tools-grid');
                            if (grid && grid.children.length === 0) {
                                grid.innerHTML = '<div class="no-saved-tools"><p>You haven\'t saved any tools yet.</p></div>';
                            }

                            // Update count
                            const countEl = document.querySelector('.saved-tools-count');
                            if (countEl) {
                                countEl.textContent = response.data.saved_count;
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Remove error:', error);
                    this.disabled = false;
                });
            });
        });
    }

    /**
     * ========================================================================
     * CLICK TRACKING FUNCTIONALITY
     * ========================================================================
     */

    function initClickTracking(container = document) {
        const visitLinks = container.querySelectorAll('.visit-site-btn:not([data-tracking-initialized])');

        visitLinks.forEach(link => {
            link.setAttribute('data-tracking-initialized', 'true');

            link.addEventListener('click', function() {
                const toolId = this.dataset.toolId;
                if (!toolId) return;

                // Fire and forget - don't block navigation
                ajaxRequest('track_tool_click', { tool_id: toolId })
                .catch(() => {});
            });
        });
    }

    /**
     * ========================================================================
     * TOAST NOTIFICATIONS
     * ========================================================================
     */

    function showToast(message, duration = 3000) {
        // Create toast container if not exists
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }

        // Create toast
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        toastContainer.appendChild(toast);

        // Animate in
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove after duration
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    /**
     * ========================================================================
     * INITIALIZATION
     * ========================================================================
     */

    function init() {
        // Initialize directory filters
        const directoryContainers = document.querySelectorAll('.ai-directory-container, .directory-section');
        directoryContainers.forEach(container => {
            new DirectoryFilter(container);
        });

        // Initialize save buttons globally
        initSaveButtons();

        // Initialize remove buttons (saved tools page)
        initRemoveSavedButtons();

        // Initialize click tracking
        initClickTracking();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose functions for external use
    window.aiOutilsDirectory = {
        initSaveButtons,
        initClickTracking,
        initRemoveSavedButtons,
        showToast
    };

})();
