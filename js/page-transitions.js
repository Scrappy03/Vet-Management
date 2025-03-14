document.addEventListener('DOMContentLoaded', function () {
    // Add page transition class to main content
    const mainContent = document.querySelector('.dashboard-main');
    if (mainContent) {
        mainContent.classList.add('page-transition');
    }

    // Create loading overlay element if not present
    if (!document.querySelector('.page-loading')) {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'page-loading';
        loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(loadingOverlay);
    }

    // Handle navigation links
    const navLinks = document.querySelectorAll('a:not([data-no-transition])');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');

        // Skip links that shouldn't have transitions
        if (!href ||
            href === '#' ||
            href.startsWith('javascript:') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            link.hasAttribute('target') ||
            link.getAttribute('role') === 'button' ||
            link.closest('.navbar-toggler') ||
            link.closest('[data-bs-toggle]')) {
            return;
        }

        // Skip the active link in nav
        if (link.classList.contains('active') && link.classList.contains('dashboard-nav-link')) {
            return;
        }

        link.addEventListener('click', function (e) {
            // Don't apply transitions for external links or modified clicks
            if (!href.includes(window.location.hostname) && href.includes('://') ||
                e.ctrlKey || e.metaKey || e.shiftKey) {
                return;
            }

            e.preventDefault();

            // Show loading overlay
            const loadingOverlay = document.querySelector('.page-loading');
            if (loadingOverlay) {
                loadingOverlay.classList.add('active');
            }

            // Navigate to the new page after a short delay
            setTimeout(() => {
                window.location.href = href;
            }, 300);
        });
    });

    // Remove any unused transition elements
    const unusedOverlays = document.querySelectorAll('.page-transition-overlay');
    unusedOverlays.forEach(overlay => {
        if (overlay && !overlay.classList.contains('required')) {
            overlay.parentNode.removeChild(overlay);
        }
    });
});

// Utility function to navigate without transition
function navigateWithoutTransition(url) {
    window.location.href = url;
}
