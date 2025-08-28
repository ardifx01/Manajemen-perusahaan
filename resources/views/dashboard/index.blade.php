@extends('layouts.app')

@section('title', 'DASHBOARD')

@section('content')
<!-- Konten Dashboard -->
<div id="dashboard-pdf-content" class="px-4 py-6">
    <!-- Statistik - Responsive Grid -->
    <!-- Made statistics grid fully responsive -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-6">
        <!-- Pendapatan (Net) -->
        <div class="bg-green-100 border border-green-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-green-900/30 dark:border-green-700">
            <div class="bg-green-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Pendapatan (Net) - Bulan Ini</div>
                <div class="text-lg md:text-xl font-bold truncate">Rp {{ number_format(($monthlySubtotal ?? 0), 0, ',', '.') }}</div>
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
                <div class="text-lg md:text-xl font-bold truncate">Rp {{ number_format(($combinedMonthlyExpense ?? ($monthlySalaryTotal ?? 0)), 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Total Karyawan -->
        <div class="bg-yellow-100 border border-yellow-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-yellow-900/30 dark:border-yellow-700">
            <div class="bg-yellow-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Total Karyawan</div>
                <div class="text-lg md:text-xl font-bold truncate">{{ $totalKaryawan ?? 0 }} <span class="font-normal text-sm md:text-base">Orang</span></div>
            </div>
        </div>

        <!-- Total Invoice -->
        <div class="bg-blue-100 border border-blue-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-blue-900/30 dark:border-blue-700">
            <div class="bg-blue-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-9 7h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Total Invoice</div>
                <div class="text-lg md:text-xl font-bold truncate">{{ $invoiceCount ?? 0 }} <span class="font-normal text-sm md:text-base">Invoice</span></div>
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

    

    
    <!-- Total Pendapatan (paling bawah) -->
    
 </div>
 @endsection


@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  const canvasEl = document.getElementById('barChart');
  if (!canvasEl) return;
  const ctx = canvasEl.getContext('2d');

  const monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const salaryMap = @json($salaryByMonth ?? []);
  const otherMap = @json($expensesByMonth ?? []);
  const revenueMap = @json($revenueNetByMonth ?? []);
  const dataPendapatan = Array.from({length:12}, (_,i)=> Number(revenueMap[i+1] ?? 0));
  const dataPengeluaran = Array.from({length:12}, (_,i)=> (Number(salaryMap[i+1] ?? 0) + Number(otherMap[i+1] ?? 0)));

  function fmtIDR(v){ try { return new Intl.NumberFormat('id-ID').format(v||0); } catch { return (v||0).toLocaleString('id-ID'); } }
  function getChartColors(){ const dark=document.documentElement.classList.contains('dark'); return { text: dark?'#e5e7eb':'#374151', grid: dark?'rgba(255,255,255,0.08)':'rgba(0,0,0,0.05)' }; }
  let colors = getChartColors();

  const gradientBlue = ctx.createLinearGradient(0,0,0,canvasEl.height);
  gradientBlue.addColorStop(0,'rgba(59,130,246,0.45)');
  gradientBlue.addColorStop(1,'rgba(59,130,246,0.05)');
  const gradientRed = ctx.createLinearGradient(0,0,0,canvasEl.height);
  gradientRed.addColorStop(0,'rgba(239,68,68,0.45)');
  gradientRed.addColorStop(1,'rgba(239,68,68,0.05)');

  const chart = new Chart(ctx, {
    type: 'line',
    data: { labels: monthLabels, datasets: [
      { label: 'Pendapatan (Net)', data: dataPendapatan, backgroundColor: gradientBlue, borderColor: 'rgba(59,130,246,1)', fill: true, borderWidth: 2, pointRadius: 2, tension: 0.35 },
      { label: 'Pengeluaran', data: dataPengeluaran, backgroundColor: gradientRed, borderColor: 'rgba(239,68,68,1)', fill: true, borderWidth: 2, pointRadius: 2, tension: 0.35 }
    ]},
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { labels: { color: colors.text } }, tooltip: { callbacks: { label: (ctx)=> `${ctx.dataset.label}: Rp ${fmtIDR(ctx.parsed.y||0)}` } } },
      scales: {
        x: { ticks: { color: colors.text }, grid: { color: colors.grid } },
        y: { beginAtZero: true, ticks: { color: colors.text, callback: (v)=> `Rp ${fmtIDR(v)}` }, grid: { color: colors.grid } }
      }
    }
  });

  function resize(){
    canvasEl.style.width = '100%';
    canvasEl.style.height = '100%';
    canvasEl.removeAttribute('width');
    canvasEl.removeAttribute('height');
    chart.resize();
    chart.update('none');
  }
  window.addEventListener('load', resize);
  setTimeout(resize, 0);
  window.addEventListener('resize', resize);
  window.addEventListener('themechange', function(){
    colors = getChartColors();
    chart.options.plugins.legend.labels.color = colors.text;
    chart.options.scales.x.ticks.color = colors.text;
    chart.options.scales.y.ticks.color = colors.text;
    chart.options.scales.x.grid.color = colors.grid;
    chart.options.scales.y.grid.color = colors.grid;
    chart.update();
  });
})();
</script>
@endpush
<!-- static dashboard: no interactive scripts below -->
