<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap');

:root {
    /* Set brand colors using Tailwind-compatible RGB values */
    --primary-50: 240, 253, 253;
    --primary-100: 204, 247, 248;
    --primary-200: 153, 238, 241;
    --primary-300: 92, 221, 226;
    --primary-400: 46, 203, 207;
    --primary-500: 32, 189, 196;
    --primary-600: 23, 154, 160;
    --primary-700: 22, 123, 128;
    --primary-800: 22, 99, 105;
    --primary-900: 22, 83, 88;
    --primary-950: 7, 54, 59;
}

/* Global Font Face */
body, .fi-body {
    font-family: 'Inter', sans-serif !important;
    background-color: #f8fafc !important;
}

.dark body, .dark .fi-body {
    background-color: #090f11 !important;
}

/* Heading Font Overrides */
h1, h2, h3, h4, h5, h6, 
.fi-header-heading, 
.fi-logo,
.fi-sidebar-group-label,
.fi-ta-header-cell {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 700 !important;
    letter-spacing: -0.02em !important;
}

/* Sidebar Aesthetics */
.fi-sidebar {
    background-color: #ffffff !important;
    border-right: 1px solid #e2e8f0 !important;
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.01) !important;
}

.dark .fi-sidebar {
    background-color: #0b1315 !important;
    border-right: 1px solid #1e293b !important;
}

/* Sidebar Navigation Items */
.fi-sidebar-item-btn {
    border-radius: 12px !important;
    margin: 4px 12px !important;
    padding: 10px 14px !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    color: #475569 !important;
}

.dark .fi-sidebar-item-btn {
    color: #94a3b8 !important;
}

/* Hover style for sidebar navigation button */
.fi-sidebar-item-btn:hover {
    background-color: #f0fdfd !important;
    color: #179aa0 !important;
}

.fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
    color: #179aa0 !important;
}

.dark .fi-sidebar-item-btn:hover {
    background-color: rgba(32, 189, 196, 0.08) !important;
    color: #2ecbcf !important;
}

.dark .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
    color: #2ecbcf !important;
}

/* Active style for sidebar navigation button */
.fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
    background-color: #20bdc4 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(32, 189, 196, 0.25) !important;
}

.fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
    color: #ffffff !important;
}

/* Sidebar group headers */
.fi-sidebar-group-label {
    text-transform: uppercase !important;
    font-size: 0.65rem !important;
    letter-spacing: 0.08em !important;
    color: #94a3b8 !important;
    padding-left: 24px !important;
    margin-top: 20px !important;
    margin-bottom: 6px !important;
}

/* Topbar Custom Styling */
.fi-topbar {
    background-color: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(12px) !important;
    border-bottom: 1px solid #f1f5f9 !important;
}

.dark .fi-topbar {
    background-color: rgba(11, 19, 21, 0.8) !important;
    backdrop-filter: blur(12px) !important;
    border-bottom: 1px solid #1e293b !important;
}

/* Cards & Panel Layout Customizations */
.fi-section, 
.fi-card,
.fi-ta-ctn,
.fi-wi-stats-overview-stat {
    border-radius: 16px !important;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.02), 0 2px 8px -1px rgba(0, 0, 0, 0.01) !important;
    background-color: #ffffff !important;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    overflow: hidden !important;
}

.dark .fi-section,
.dark .fi-card,
.dark .fi-ta-ctn,
.dark .fi-wi-stats-overview-stat {
    border: 1px solid rgba(30, 41, 59, 0.8) !important;
    background-color: #0b1315 !important;
}

/* Hover effects for dashboard stats overview cards */
.fi-wi-stats-overview-stat:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 28px -5px rgba(32, 189, 196, 0.08), 0 8px 16px -6px rgba(0, 0, 0, 0.02) !important;
}

/* Remove default division borders and shadows in stats overview widget container */
.fi-wi-stats-overview {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

.fi-wi-stats-overview .fi-section {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

.fi-wi-stats-overview .fi-section-content-ctn,
.fi-wi-stats-overview .fi-section-content {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

.fi-wi-stats-overview > div {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
    gap: 16px !important;
}

.fi-wi-stats-overview > div > * {
    /* Reset default division border boundaries between grid items */
    border-left: none !important;
    border-top: none !important;
    border-right: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

/* Simple Card layout (Login and Auth screens) */
.fi-simple-card {
    border-radius: 20px !important;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: 0 10px 30px -5px rgba(32, 189, 196, 0.08), 0 5px 15px -5px rgba(0, 0, 0, 0.03) !important;
}

.dark .fi-simple-card {
    border: 1px solid rgba(30, 41, 59, 0.8) !important;
    background-color: #0b1315 !important;
}

/* Forms: Inputs styling */
.fi-input-wrp {
    border-radius: 10px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
    transition: all 0.2s ease !important;
}

.dark .fi-input-wrp {
    border: 1px solid #334155 !important;
    background-color: #121e21 !important;
}

.fi-input-wrp:focus-within {
    border-color: #20bdc4 !important;
    box-shadow: 0 0 0 3px rgba(32, 189, 196, 0.15) !important;
}

/* Buttons styling */
.fi-btn {
    border-radius: 10px !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    font-weight: 600 !important;
}

.fi-btn:hover {
    transform: translateY(-1px) !important;
}

/* Badges styling */
.fi-badge {
    border-radius: 8px !important;
    padding: 4px 10px !important;
    font-weight: 600 !important;
}

/* Table styling */
.fi-ta-header-cell {
    font-size: 0.725rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.06em !important;
    color: #64748b !important;
    background-color: #f8fafc !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 14px 18px !important;
}

.dark .fi-ta-header-cell {
    color: #94a3b8 !important;
    background-color: #0e171a !important;
    border-bottom: 1px solid #1e293b !important;
}

.fi-ta-row {
    transition: background-color 0.15s ease !important;
}

.fi-ta-row:hover {
    background-color: #f8fcfd !important;
}

.dark .fi-ta-row:hover {
    background-color: rgba(32, 189, 196, 0.02) !important;
}

/* Page titles layout */
.fi-header {
    margin-bottom: 24px !important;
}

/* Force active toggle switch to match brand theme color and ensure visibility */
.fi-toggle[aria-checked="true"],
.fi-toggle-on,
button.fi-toggle[aria-checked="true"],
button.fi-toggle-on {
    background-color: #20bdc4 !important;
}

/* Ensure active toggle knob is white */
.fi-toggle[aria-checked="true"] > span,
.fi-toggle-on > span,
button.fi-toggle[aria-checked="true"] > span,
button.fi-toggle-on > span {
    background-color: #ffffff !important;
}

/* Inactive toggle state styling for better visibility and contrast */
.fi-toggle[aria-checked="false"],
button.fi-toggle[aria-checked="false"] {
    background-color: #cbd5e1 !important; /* Slate-300 track for clear visibility when inactive */
}

/* Inactive toggle knob */
.fi-toggle[aria-checked="false"] > span,
button.fi-toggle[aria-checked="false"] > span {
    background-color: #ffffff !important;
}
</style>

<script>
(function () {
    // Store the old select value when the user focuses it
    document.addEventListener('focusin', function (event) {
        const select = event.target.closest('tbody select');
        if (select) {
            if (select.dataset.oldValue === undefined) {
                select.dataset.oldValue = select.value;
            }
        }
    }, true);

    function handleSelectVerification(event) {
        const select = event.target.closest('tbody select');
        if (!select) return;

        // If the value has not changed compared to the last confirmed value, do nothing
        if (select.value === select.dataset.oldValue) {
            return;
        }

        // If we already have a decision for this event cycle, apply it
        if (select.dataset.confirmResult === 'cancel') {
            event.preventDefault();
            event.stopPropagation();
            select.value = select.dataset.oldValue;
            return;
        }
        if (select.dataset.confirmResult === 'ok') {
            select.dataset.oldValue = select.value;
            return;
        }

        // Otherwise, prompt the user
        let fieldName = 'data';
        if (select.querySelector('option[value="admin"]')) {
            fieldName = 'peran (role)';
        } else if (select.querySelector('option[value="suspended"]')) {
            fieldName = 'status akun';
        }

        const confirmed = confirm('Apakah Anda yakin ingin mengubah ' + fieldName + ' pengguna ini?');
        if (!confirmed) {
            select.dataset.confirmResult = 'cancel';
            event.preventDefault();
            event.stopPropagation();
            select.value = select.dataset.oldValue;
            
            // Clear the decision flag shortly after this event cycle completes
            setTimeout(() => {
                delete select.dataset.confirmResult;
            }, 50);
        } else {
            select.dataset.confirmResult = 'ok';
            select.dataset.oldValue = select.value;
            
            // Clear the decision flag shortly after this event cycle completes
            setTimeout(() => {
                delete select.dataset.confirmResult;
            }, 50);
        }
    }

    // Intercept both input and change events during the capturing phase
    document.addEventListener('input', handleSelectVerification, true);
    document.addEventListener('change', handleSelectVerification, true);

    // Intercept the click event on toggle switches in the table body
    document.addEventListener('click', function (event) {
        const toggle = event.target.closest('tbody button.fi-toggle');
        if (toggle) {
            if (!confirm('Apakah Anda yakin ingin mengubah status verifikasi (centang biru) pengguna ini?')) {
                event.preventDefault();
                event.stopPropagation();
            }
        }
    }, true);
})();
</script>
