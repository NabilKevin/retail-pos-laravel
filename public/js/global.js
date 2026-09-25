
// Initialize Lucide icons with error handling
function initIcons() {
    try {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        } else {
            console.error("[v0] Lucide library not loaded");
        }
    } catch (error) {
        console.error("[v0] Error initializing icons:", error);
    }
}

// Theme system with enhanced functionality
function initTheme() {
    try {
        const savedTheme = localStorage.getItem('theme') || 'light';

        document.documentElement.className = savedTheme;
        updateThemeIcon();

    } catch (error) {
        console.error("[v0] Error initializing theme:", error);
        // Fallback to light theme
        document.documentElement.className = 'light';
    }
}

function toggleTheme() {
    try {
        const currentTheme = document.documentElement.className;
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';


        document.documentElement.className = newTheme;
        localStorage.setItem('theme', newTheme);
        updateThemeIcon();

        // Add visual feedback
        const button = document.querySelector('[onclick="toggleTheme()"]');
        if (button) {
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = '';
            }, 150);
        }

    } catch (error) {
        console.error("[v0] Error toggling theme:", error);
    }
}

function updateThemeIcon() {
    try {
        const themeIcon = document.getElementById('theme-icon');
        const isDark = document.documentElement.className === 'dark';

        if (themeIcon) {
            themeIcon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
            initIcons(); // Re-initialize icons
        }
    } catch (error) {
        console.error("[v0] Error updating theme icon:", error);
    }
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initTheme();
    initIcons();
});

// Also initialize icons when the script loads
window.addEventListener('load', function() {
    initIcons();
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (window.innerWidth < 1024) { // Mobile/tablet
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    } else { // Desktop
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');

        // Toggle text visibility
        const sidebarTexts = sidebar.querySelectorAll('.sidebar-text');
        const sidebarLabels = sidebar.querySelectorAll('.sidebar-label');

        sidebarTexts.forEach(text => text.classList.toggle('hidden'));
        sidebarLabels.forEach(label => label.classList.toggle('hidden'));
    }
}

// Close sidebar on window resize if mobile
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (window.innerWidth >= 1024) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
});

// Simple modal functionality
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function formatInputNumber(e, hargaRaw) {
    let raw = e.target.value.replace(/\D/g, "");
    if(raw === "") {
        e.target.value = "";
        hargaRaw.value = "";
        return;
    }
    let number = parseInt(raw, 10);
    e.target.value = new Intl.NumberFormat('id-ID').format(number);
    hargaRaw.value = number; // 👉 yang dikirim ke server angka murni
}

function parseNumber(str) {
  return parseInt(str.replace(/[^0-9-]/g, "")) || 0
}

function formatNumber(value) {
  return new Intl.NumberFormat('id-ID').format(value || 0)
}