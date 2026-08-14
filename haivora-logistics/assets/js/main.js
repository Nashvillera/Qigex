/**
 * Main JavaScript for Haivora Logistics Theme
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Sticky Header Scroll Enhancement
    const siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 20) {
                siteHeader.style.boxShadow = '0 8px 24px rgba(15, 23, 42, 0.12)';
            } else {
                siteHeader.style.boxShadow = 'var(--shadow-sm)';
            }
        });
    }

    // Smooth Scroll for Internal Anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Tracking Header Tabs
    const trackTabs = document.querySelectorAll('.track-tab');
    trackTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            trackTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const inputField = document.getElementById('tracking-input-field');
            if (inputField) {
                const tabType = this.getAttribute('data-tab');
                if (tabType === 'number') {
                    inputField.placeholder = 'Enter tracking number (e.g., QX-8829-US)...';
                } else if (tabType === 'bol') {
                    inputField.placeholder = 'Enter Bill of Lading ID (e.g., BOL-9901-QID)...';
                } else if (tabType === 'container') {
                    inputField.placeholder = 'Enter Container Serial (e.g., MSKU-402910-2)...';
                }
            }
        });
    });

});
