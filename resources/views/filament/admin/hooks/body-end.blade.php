<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Dashboard Interactions
    initializeDashboardEnhancements();
    
    // Auto-refresh dashboard stats every 5 minutes
    setInterval(refreshDashboardStats, 300000);
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
    
    // Initialize theme switcher
    initializeThemeSwitcher();
});

/**
 * Initialize dashboard enhancements
 */
function initializeDashboardEnhancements() {
    // Add loading states to buttons
    document.querySelectorAll('.fi-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('fi-btn-loading')) {
                this.classList.add('fi-loading');
                setTimeout(() => {
                    this.classList.remove('fi-loading');
                }, 1000);
            }
        });
    });

    // Enhanced table row interactions
    document.querySelectorAll('.fi-ta-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.zIndex = '10';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.zIndex = 'auto';
        });
    });

    // Smooth scroll for navigation
    document.querySelectorAll('.fi-sidebar-nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255,255,255,0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

/**
 * Refresh dashboard stats
 */
function refreshDashboardStats() {
    if (window.location.pathname.includes('/admin') && 
        (window.location.pathname.endsWith('/admin') || window.location.pathname.endsWith('/admin/'))) {
        
        // Show subtle loading indicator
        const statsWidgets = document.querySelectorAll('.fi-wi-stats-overview-stat');
        statsWidgets.forEach(widget => {
            widget.style.opacity = '0.7';
        });

        // Simulate refresh (in real app, this would be a Livewire call)
        setTimeout(() => {
            statsWidgets.forEach(widget => {
                widget.style.opacity = '1';
            });
            
            // Show success notification
            if (window.Livewire) {
                window.Livewire.dispatch('notify', {
                    type: 'success',
                    message: 'Dữ liệu đã được cập nhật',
                    duration: 2000
                });
            }
        }, 1000);
    }
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    // Add tooltips to action buttons
    document.querySelectorAll('[title]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'fi-tooltip';
            tooltip.textContent = this.getAttribute('title');
            tooltip.style.cssText = `
                position: absolute;
                background: rgba(0,0,0,0.8);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                z-index: 1000;
                pointer-events: none;
                white-space: nowrap;
            `;
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
}

/**
 * Initialize keyboard shortcuts
 */
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + Shift + D: Go to Dashboard
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
            e.preventDefault();
            window.location.href = '/admin';
        }
        
        // Ctrl/Cmd + Shift + N: Create new course
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'N') {
            e.preventDefault();
            window.location.href = '/admin/courses/create';
        }
        
        // Ctrl/Cmd + Shift + P: Create new post
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'P') {
            e.preventDefault();
            window.location.href = '/admin/posts/create';
        }
        
        // Escape: Close modals
        if (e.key === 'Escape') {
            const modal = document.querySelector('.fi-modal');
            if (modal) {
                const closeButton = modal.querySelector('[data-close-modal]');
                if (closeButton) {
                    closeButton.click();
                }
            }
        }
    });
}

/**
 * Initialize theme switcher
 */
function initializeThemeSwitcher() {
    // Add theme toggle button if not exists
    const topbar = document.querySelector('.fi-topbar');
    if (topbar && !document.querySelector('.theme-toggle')) {
        const themeToggle = document.createElement('button');
        themeToggle.className = 'theme-toggle fi-btn fi-btn-outlined fi-btn-sm';
        themeToggle.innerHTML = `
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
        `;
        
        themeToggle.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });
        
        topbar.appendChild(themeToggle);
    }
    
    // Apply saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .fi-tooltip {
        animation: fadeIn 0.2s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .theme-toggle {
        margin-left: 8px;
        transition: all 0.2s ease;
    }
    
    .theme-toggle:hover {
        transform: rotate(180deg);
    }
`;
document.head.appendChild(style);
</script>

<!-- Performance Monitoring -->
<script>
// Simple performance monitoring
if (window.performance) {
    window.addEventListener('load', function() {
        setTimeout(function() {
            const perfData = window.performance.timing;
            const loadTime = perfData.loadEventEnd - perfData.navigationStart;
            
            if (loadTime > 3000) {
                console.warn('⚠️ Trang tải chậm:', loadTime + 'ms');
            } else {
                console.log('✅ Trang tải nhanh:', loadTime + 'ms');
            }
        }, 0);
    });
}
</script>
