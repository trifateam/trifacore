// Alpine.js + Collapse plugin
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;
