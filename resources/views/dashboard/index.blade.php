@extends('layouts.app')

@section('title', 'DASHBOARD')

@section('content')
<!-- Konten Dashboard -->
<div id="dashboard-pdf-content" class="px-4 py-6">
    <!-- Statistik - Responsive Grid -->
    <!-- Made statistics grid fully responsive -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-6">
        <!-- Pendapatan -->
        <div class="bg-green-100 border border-green-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-green-900/30 dark:border-green-700">
            <div class="bg-green-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Pendapatan</div>
                <div class="text-lg md:text-xl font-bold truncate">Rp 0</div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-red-100 border border-red-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-red-900/30 dark:border-red-700">
            <div class="bg-red-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16c1.333 0 4-1 4-4s-2.667-4-4-4-4 1-4 4 2.667 4 4 4z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Pengeluaran</div>
                <div class="text-lg md:text-xl font-bold truncate">Rp 0</div>
            </div>
        </div>

        <!-- Stok Barang -->
        <div class="bg-yellow-100 border border-yellow-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-yellow-900/30 dark:border-yellow-700">
            <div class="bg-yellow-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l9-7 9 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Stok Barang</div>
                <div class="text-lg md:text-xl font-bold truncate">0 Unit</div>
            </div>
        </div>

        <!-- Barang Masuk -->
        <div class="bg-blue-100 border border-blue-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-blue-900/30 dark:border-blue-700">
            <div class="bg-blue-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v4H4V4zM4 12h16v8H4v-8z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Barang Masuk</div>
                <div class="text-lg md:text-xl font-bold truncate">0 Unit</div>
            </div>
        </div>

        <!-- Barang Keluar -->
        <div class="bg-purple-100 border border-purple-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow sm:col-span-2 lg:col-span-1 dark:bg-purple-900/30 dark:border-purple-700">
            <div class="bg-purple-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 16l4-4-4-4m0 8V4"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Barang Keluar</div>
                <div class="text-lg md:text-xl font-bold truncate">0 Unit</div>
            </div>
        </div>

        <!-- Tombol Print dan PDF - Responsive -->
        <!-- Made buttons responsive and properly positioned -->
        
    </div>

    <!-- Grafik - Responsive -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-6 shadow dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-base md:text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Statistik Barang Masuk & Keluar</h2>
        <!-- Made chart container responsive -->
        <div class="relative h-64 md:h-80 lg:h-96">
            <canvas id="barChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    const canvasEl = document.getElementById('barChart');
    const ctx = canvasEl.getContext('2d');

    function forceChartResize(chartInstance) {
        // Let Chart.js compute from CSS box; clear fixed attributes that can lock size
        canvasEl.style.width = '100%';
        canvasEl.style.height = '100%';
        canvasEl.removeAttribute('width');
        canvasEl.removeAttribute('height');
        chartInstance.resize();
        chartInstance.update('none');
    }

    

    function getChartColors() {
        const dark = document.documentElement.classList.contains('dark');
        return {
            text: dark ? '#e5e7eb' : '#374151', // gray-200 vs gray-700
            grid: dark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.05)',
            border: dark ? '#374151' : '#e5e7eb'
        };
    }

    let colors = getChartColors();

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: [10, 20, 15, 30, 25, 40],
                    backgroundColor: 'rgba(59, 130, 246, 0.6)'
                },
                {
                    label: 'Barang Keluar',
                    data: [5, 15, 10, 20, 15, 30],
                    backgroundColor: 'rgba(139, 92, 246, 0.6)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: colors.text,
                        font: {
                            size: window.innerWidth < 768 ? 12 : 14
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: colors.text,
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    },
                    grid: {
                        color: colors.grid
                    }
                },
                x: {
                    ticks: {
                        color: colors.text,
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    },
                    grid: {
                        color: colors.grid
                    }
                }
            }
        }
    });

    // Ensure correct size on initial load and after styles settle
    window.addEventListener('load', () => forceChartResize(chart));
    setTimeout(() => forceChartResize(chart), 0);
    // When tab becomes visible again, re-sync size
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            forceChartResize(chart);
        }
    });

    // Keep chart responsive to container changes (e.g., sidebar toggle)
    const container = canvasEl.parentElement;
    // After CSS transitions (e.g., sidebar width) complete, re-measure
    container.addEventListener('transitionend', (e) => {
        if (e.propertyName === 'width' || e.propertyName === 'left' || e.propertyName === 'right' || e.propertyName === 'transform') {
            forceChartResize(chart);
        }
    });
    if (window.ResizeObserver) {
        const ro = new ResizeObserver(() => {
            // update font sizes based on current width
            const small = window.innerWidth < 768;
            chart.options.plugins.legend.labels.font.size = small ? 12 : 14;
            chart.options.scales.x.ticks.font.size = small ? 10 : 12;
            chart.options.scales.y.ticks.font.size = small ? 10 : 12;
            forceChartResize(chart);
        });
        ro.observe(container);
    } else {
        window.addEventListener('resize', function() {
            const small = window.innerWidth < 768;
            chart.options.plugins.legend.labels.font.size = small ? 12 : 14;
            chart.options.scales.x.ticks.font.size = small ? 10 : 12;
            chart.options.scales.y.ticks.font.size = small ? 10 : 12;
            forceChartResize(chart);
        });
    }

    // Explicitly handle custom events and orientation changes
    window.addEventListener('forcechartresize', () => {
        const small = window.innerWidth < 768;
        chart.options.plugins.legend.labels.font.size = small ? 12 : 14;
        chart.options.scales.x.ticks.font.size = small ? 10 : 12;
        chart.options.scales.y.ticks.font.size = small ? 10 : 12;
        forceChartResize(chart);
    });

    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            const small = window.innerWidth < 768;
            chart.options.plugins.legend.labels.font.size = small ? 12 : 14;
            chart.options.scales.x.ticks.font.size = small ? 10 : 12;
            chart.options.scales.y.ticks.font.size = small ? 10 : 12;
            forceChartResize(chart);
        }, 200);
    });

    // Detect browser zoom changes (devicePixelRatio change) and force redraw
    (function watchDPR() {
        let last = window.devicePixelRatio;
        const onResize = () => {
            if (window.devicePixelRatio !== last) {
                last = window.devicePixelRatio;
                forceChartResize(chart);
            }
        };
        window.addEventListener('resize', onResize);
        // also poll lightly as some browsers may not fire resize on zoom changes
        setInterval(onResize, 500);
    })();

    // Update chart colors when theme changes
    window.addEventListener('themechange', function() {
        colors = getChartColors();
        chart.options.plugins.legend.labels.color = colors.text;
        chart.options.scales.x.ticks.color = colors.text;
        chart.options.scales.y.ticks.color = colors.text;
        chart.options.scales.x.grid.color = colors.grid;
        chart.options.scales.y.grid.color = colors.grid;
        chart.update();
    });

    function downloadPDF() {
        const element = document.getElementById('dashboard-pdf-content');
        const opt = {
            margin: 0.3,
            filename: 'dashboard.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: window.innerWidth < 768 ? 1 : 2,
                useCORS: true
            },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>
@endpush
