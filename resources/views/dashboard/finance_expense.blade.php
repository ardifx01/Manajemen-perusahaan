@extends('layouts.app')

@section('title', 'Laporan Pengeluaran')

@section('content')
<div x-data="expensePage({{ json_encode(['month' => $bulanNow ?? now()->month, 'year' => $tahunNow ?? now()->year]) }}, @js($salaryByEmployee ?? []), @js($otherExpensesMonthly ?? []), @js($monthlySalaryTotal ?? 0), @js($monthlyOtherExpenseTotal ?? 0), @js($salaryByMonth ?? []), @js($expensesByMonth ?? []))" x-init="init()" class="min-h-[70vh]">
    <!-- Filters -->
    <div class="sticky top-16 z-20 -mx-6 md:-mx-10 px-6 md:px-10 py-3 bg-white/90 backdrop-blur border-b border-gray-100 dark:bg-gray-900/80 dark:border-gray-800">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Laporan Pengeluaran</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gaji karyawan dan pengeluaran lain</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <select x-model="filters.month" @change="onFilterChange" class="no-arrow appearance-none bg-white border border-gray-200 text-gray-700 text-sm rounded-lg pl-9 pr-9 py-2 shadow-sm hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:border-blue-400 dark:focus:ring-blue-400">
                        <template x-for="(m, idx) in months" :key="idx">
                            <option :value="String(idx+1)" :selected="String(idx+1)===filters.month" x-text="m"></option>
                        </template>
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 left-2 flex items-center text-gray-400 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400 dark:text-gray-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </span>
                </div>
                <!-- Filter Tahun (hidden untuk filtering, tapi tetap ada) -->
                <select x-model="filters.year" @change="onFilterChange" class="hidden">
                    <template x-for="y in allYears" :key="y">
                        <option :value="String(y)" :selected="String(y)===filters.year" x-text="y"></option>
                    </template>
                </select>
                <!-- Link Pilih Tahun -->
                <button type="button" @click="openYearModal()" 
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-700 dark:hover:bg-indigo-900/50">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span x-text="'Pilih Tahun (' + filters.year + ')'"></span>
                </button>
                <a :href="filterUrl()" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-sm hover:shadow transition dark:bg-blue-600/80 dark:hover:bg-blue-500">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Terapkan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
        <div class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 transition dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">Gaji (Bulan Ini)</p>
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition dark:bg-gray-700 dark:text-emerald-300 dark:group-hover:bg-gray-600"><i class="fa-solid fa-users"></i></span>
            </div>
            <div class="mt-3 text-2xl font-semibold text-gray-800 dark:text-gray-100" x-text="formatCurrency(monthlySalaryTotal)"></div>
        </div>
        <div class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 transition dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pengeluaran Lain (Bulan Ini)</p>
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-100 transition dark:bg-gray-700 dark:text-rose-300 dark:group-hover:bg-gray-600"><i class="fa-solid fa-file-invoice-dollar"></i></span>
            </div>
            <div class="mt-3 text-2xl font-semibold text-gray-800 dark:text-gray-100" x-text="formatCurrency(monthlyOtherExpenseTotal)"></div>
        </div>
        <div class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 transition dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengeluaran (Bulan Ini)</p>
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 transition dark:bg-gray-700 dark:text-indigo-300 dark:group-hover:bg-gray-600"><i class="fa-solid fa-scale-balanced"></i></span>
            </div>
            <div class="mt-3 text-2xl font-semibold text-gray-800 dark:text-gray-100" x-text="formatCurrency(monthlySalaryTotal + monthlyOtherExpenseTotal)"></div>
        </div>
    </div>

    

    <!-- Rekap Tahunan -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            <div class="px-5 py-4">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Rekap Gaji per Bulan ({{ $tahunNow }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs dark:bg-gray-900/40 dark:text-gray-300">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold">Bulan</th>
                            <th class="text-right px-5 py-3 font-semibold">Total</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="(label, idx) in months" :key="idx">
                            <tr>
                                <td class="px-5 py-3 dark:text-gray-300" x-text="label"></td>
                                <td class="px-5 py-3 text-right dark:text-gray-200" x-text="formatCurrency(salaryByMonth[idx+1]||0)"></td>
                                <td class="px-5 py-3 text-right">
                                    <a href="#" @click.prevent="openExpenseDetail('salary', idx+1, Number(filters.year))" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Lihat detail</a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            <div class="px-5 py-4">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Rekap Pengeluaran Lain per Bulan ({{ $tahunNow }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs dark:bg-gray-900/40 dark:text-gray-300">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold">Bulan</th>
                            <th class="text-right px-5 py-3 font-semibold">Total</th>
                            <th class="text-right px-5 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="(label, idx) in months" :key="idx">
                            <tr>
                                <td class="px-5 py-3 dark:text-gray-300" x-text="label"></td>
                                <td class="px-5 py-3 text-right dark:text-gray-200" x-text="formatCurrency(expensesByMonth[idx+1]||0)"></td>
                                <td class="px-5 py-3 text-right">
                                    <a href="#" @click.prevent="openExpenseDetail('other', idx+1, Number(filters.year))" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Lihat detail</a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail (dipindah ke dalam scope x-data agar state Alpine terbaca) -->
    <div x-show="detailOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="closeDetail()"></div>
        <div class="relative bg-white w-[92vw] max-w-5xl rounded-2xl shadow-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100">
            <div class="px-5 py-3 border-b flex items-center justify-between dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100" x-text="detailTitle"></h3>
                <button class="h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" @click="closeDetail()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-5">
                <template x-if="detailLoading">
                    <div class="py-10 text-center text-gray-500 dark:text-gray-400">Memuat data...</div>
                </template>
                <template x-if="!detailLoading">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs dark:bg-gray-900/40 dark:text-gray-300">
                                <tr x-show="detailType==='salary'">
                                    <th class="text-left px-3 py-2">Karyawan</th>
                                    <th class="text-right px-3 py-2">Total Gaji</th>
                                </tr>
                                <tr x-show="detailType==='other'">
                                    <th class="text-left px-3 py-2">Tanggal</th>
                                    <th class="text-left px-3 py-2">Jenis</th>
                                    <th class="text-left px-3 py-2">Deskripsi</th>
                                    <th class="text-right px-3 py-2">Jumlah</th>
                                </tr>
                            </thead>
                            <!-- Tabel body untuk tipe salary -->
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-show="detailType==='salary'">
                                <template x-if="!detailRows.length">
                                    <tr>
                                        <td colspan="2" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada data.</td>
                                    </tr>
                                </template>
                                <template x-for="(r, i) in detailRows" :key="i">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-3 py-2 dark:text-gray-300" x-text="r.employee"></td>
                                        <td class="px-3 py-2 text-right dark:text-gray-200" x-text="formatCurrency(r.total_gaji)"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <!-- Tabel body untuk tipe other -->
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-show="detailType==='other'">
                                <template x-if="!detailRows.length">
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada data.</td>
                                    </tr>
                                </template>
                                <template x-for="(r, i) in detailRows" :key="i">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-3 py-2 dark:text-gray-300" x-text="new Date(r.tanggal).toLocaleDateString('id-ID')"></td>
                                        <td class="px-3 py-2 dark:text-gray-300" x-text="r.jenis"></td>
                                        <td class="px-3 py-2 dark:text-gray-300" x-text="r.deskripsi"></td>
                                        <td class="px-3 py-2 text-right dark:text-gray-200" x-text="formatCurrency(r.amount)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
            <div class="px-5 py-3 border-t bg-gray-50 text-right dark:border-gray-700 dark:bg-gray-900/40">
                <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm bg-gray-700 text-white hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600" @click="closeDetail()">Tutup</button>
            </div>
        </div>
    </div>
    
    <!-- Modal Pilih Tahun -->
    <div x-show="yearModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="closeYearModal()"></div>
        <div class="relative bg-white w-[92vw] max-w-lg rounded-2xl shadow-lg overflow-hidden dark:bg-gray-800">
            <div class="px-5 py-4 border-b flex items-center justify-between dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pilih Tahun</h3>
                <button type="button" @click="closeYearModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-4 gap-2 max-h-60 overflow-y-auto">
                    <template x-for="year in allYears" :key="year">
                        <button type="button" @click="selectYear(year)" 
                                class="px-3 py-2 text-sm font-medium rounded-md border transition-colors"
                                :class="String(year) === filters.year ? 
                                        'bg-indigo-600 text-white border-indigo-600' : 
                                        'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600'"
                                x-text="year">
                        </button>
                    </template>
                </div>
            </div>
            <div class="px-5 py-3 border-t bg-gray-50 text-right dark:border-gray-700 dark:bg-gray-900/40">
                <button type="button" @click="closeYearModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function expensePage(initialFilters, salaryRows, otherExpRows, salaryTotal, otherTotal, salaryByMonth, expensesByMonth) {
    return {
        months: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        allYears: @js($allYears ?? []),
        yearModalOpen: false,
        filters: { month: String(initialFilters.month), year: String(initialFilters.year) },
        salaryByMonth: salaryByMonth || {},
        expensesByMonth: expensesByMonth || {},
        monthlySalaryTotal: Number(salaryTotal||0),
        monthlyOtherExpenseTotal: Number(otherTotal||0),
        // Modal state
        detailOpen: false,
        detailLoading: false,
        detailTitle: '',
        detailType: 'salary',
        detailRows: [],
        init(){},
        formatCurrency(v){ try { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v||0); } catch(e){ return `Rp ${Number(v||0).toLocaleString('id-ID')}` } },
        filterUrl(){
            const p = new URLSearchParams();
            p.set('month', Number(this.filters.month));
            p.set('year', Number(this.filters.year));
            return `{{ route('finance.expense') }}` + '?' + p.toString();
        },
        onFilterChange(){ /* noop: user must click Terapkan */ },
        async openExpenseDetail(type, month, year){
            try{
                this.detailOpen = true; this.detailLoading = true; this.detailRows = []; this.detailType = type;
                const p = new URLSearchParams();
                p.set('type', type); p.set('month', month); p.set('year', year);
                const url = `{{ route('finance.expense.detail') }}` + '?' + p.toString();
                const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
                const json = await res.json();
                this.detailTitle = `Detail Pengeluaran - ${this.months[month-1]} ${year}` + (type==='salary'? ' · Gaji' : ' · Lainnya');
                this.detailRows = Array.isArray(json.data)? json.data : [];
            }catch(e){ console.error(e); }
            finally{ this.detailLoading = false; }
        },
        closeDetail(){ this.detailOpen = false; this.detailRows = []; },
        openYearModal(){ this.yearModalOpen = true; },
        closeYearModal(){ this.yearModalOpen = false; },
        selectYear(year){
            this.filters.year = String(year);
            this.closeYearModal();
        }
    }
}
</script>
@endpush
@endsection

