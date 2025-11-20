/**
 * AI Outils Theme - Main JavaScript
 *
 * @package AI_Outils
 */

(function() {
    'use strict';

    /**
     * Mobile menu toggle
     */
    function initMobileMenu() {
        const toggle = document.querySelector('.mobile-menu-toggle');
        const nav = document.querySelector('.main-nav');

        if (toggle && nav) {
            toggle.addEventListener('click', function() {
                nav.classList.toggle('active');
                toggle.setAttribute('aria-expanded', nav.classList.contains('active'));
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                    nav.classList.remove('active');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && nav.classList.contains('active')) {
                    nav.classList.remove('active');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href === '#') return;

                const target = document.querySelector(href);

                if (target) {
                    e.preventDefault();

                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Add loading state to forms
     */
    function initFormLoadingStates() {
        const forms = document.querySelectorAll('form');

        forms.forEach(function(form) {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');

                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Loading...';

                    // Reset after 10 seconds as fallback
                    setTimeout(function() {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                        submitBtn.textContent = originalText;
                    }, 10000);
                }
            });
        });
    }

    /**
     * Lazy load images with loading attribute
     */
    function initLazyLoading() {
        // Fallback for browsers that don't support loading="lazy"
        if ('loading' in HTMLImageElement.prototype) {
            return; // Browser supports native lazy loading
        }

        const images = document.querySelectorAll('img[loading="lazy"]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        image.src = image.dataset.src || image.src;
                        image.classList.add('loaded');
                        observer.unobserve(image);
                    }
                });
            });

            images.forEach(function(image) {
                imageObserver.observe(image);
            });
        } else {
            // Fallback: load all images
            images.forEach(function(image) {
                image.src = image.dataset.src || image.src;
            });
        }
    }

    /**
     * Add sticky header on scroll
     */
    function initStickyHeader() {
        const header = document.querySelector('.site-header');

        if (!header) return;

        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                header.classList.remove('scroll-up');
                header.classList.remove('scroll-down');
                return;
            }

            if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
                // Scrolling down
                header.classList.remove('scroll-up');
                header.classList.add('scroll-down');
            } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
                // Scrolling up
                header.classList.remove('scroll-down');
                header.classList.add('scroll-up');
            }

            lastScroll = currentScroll;
        });
    }

    /**
     * Initialize all functions when DOM is ready
     */
    function init() {
        initMobileMenu();
        initSmoothScroll();
        initFormLoadingStates();
        initLazyLoading();
        initStickyHeader();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
