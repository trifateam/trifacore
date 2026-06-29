// Alpine.js + Collapse plugin
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
Alpine.plugin(collapse);

// Dark Mode + App State — registered BEFORE Alpine.start()
Alpine.data('darkMode', () => ({
    isDark: false,
    sidebarOpen: false,
    init() {
        const stored = localStorage.getItem('darkMode');
        if (stored !== null) {
            this.isDark = stored === 'true';
        } else {
            this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        this.applyDark();
        this.$watch('isDark', () => this.applyDark());
    },
    toggle() {
        this.isDark = !this.isDark;
        localStorage.setItem('darkMode', this.isDark);
    },
    applyDark() {
        document.documentElement.classList.toggle('dark', this.isDark);
    }
}));

window.Alpine = Alpine;
Alpine.start();

// Heavy libraries — loaded async AFTER Alpine is ready (non-blocking)
import('chart.js/auto').then(m => { window.Chart = m.default; });
import('sweetalert2').then(m => { window.Swal = m.default; });
