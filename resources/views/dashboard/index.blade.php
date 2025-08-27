@extends('layouts.app')

@section('title', 'DASHBOARD')

@section('content')
<!-- Konten Dashboard -->
<div id="dashboard-pdf-content" class="px-4 py-6">
    <!-- Statistik - Responsive Grid -->
    <!-- Made statistics grid fully responsive -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-6">
        <!-- Pendapatan (Net) -->
        <div id="stat-pendapatan" role="button" tabindex="0"
             class="bg-green-100 border border-green-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-green-900/30 dark:border-green-700 cursor-pointer hover:ring hover:ring-emerald-300/60 focus:outline-none focus:ring focus:ring-emerald-300/70">
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
        <div id="stat-pengeluaran" role="button" tabindex="0"
             class="bg-red-100 border border-red-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-red-900/30 dark:border-red-700 cursor-pointer hover:ring hover:ring-rose-300/60 focus:outline-none focus:ring focus:ring-rose-300/70">
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
        <a href="{{ route('employee.index') }}" class="bg-yellow-100 border border-yellow-300 rounded-xl p-4 md:p-5 flex items-center space-x-3 md:space-x-4 shadow dark:bg-yellow-900/30 dark:border-yellow-700 cursor-pointer hover:ring hover:ring-amber-300/60 focus:outline-none focus:ring focus:ring-amber-300/70" role="link">
            <div class="bg-yellow-500 text-white p-2 md:p-3 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-gray-600 dark:text-gray-300 text-sm md:text-base">Total Karyawan</div>
                <div class="text-lg md:text-xl font-bold truncate">{{ $totalKaryawan ?? 0 }} <span class="font-normal text-sm md:text-base">Orang</span></div>
            </div>
        </a>

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

    <!-- MODAL: Tambah Pengeluaran -->
    <div id="modal-add-expense" class="fixed inset-0 bg-black/40 z-40 hidden">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Tambah Pengeluaran</h3>
                    <button id="close-add-expense" type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-300">✕</button>
                </div>
                <form method="POST" action="{{ route('expenses.store') }}" class="p-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Jenis</label>
                        <input type="text" name="jenis" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900" placeholder="Contoh: Listrik, Sewa, ATK" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Deskripsi</label>
                        <input type="text" name="deskripsi" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900" placeholder="Opsional">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Jumlah (Rp)</label>
                        <input type="number" name="amount" min="0" step="1" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900" required>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" id="cancel-add-expense" class="px-3 py-1.5 rounded-md border bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-3 py-1.5 rounded-md border bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Pengeluaran (Gaji Karyawan) - diletakkan di bawah chart (hidden default) -->
    <div id="report-pengeluaran" class="hidden mt-4 bg-white border border-gray-200 rounded-xl p-4 md:p-6 shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative flex items-center justify-between mb-3">
            <h2 class="text-base md:text-lg font-semibold text-gray-800 dark:text-gray-100 absolute left-1/2 -translate-x-1/2 w-full text-center pointer-events-none">Laporan Pengeluaran</h2>
            <div class="ml-auto flex gap-2 items-center">
                <button id="btn-add-expense" type="button" class="px-2.5 py-1.5 rounded-md text-xs md:text-sm border bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700" aria-label="Tambah Pengeluaran" title="Tambah Pengeluaran"><span class="text-base leading-none">+</span></button>
                @php(
                    $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']
                )
                <form method="GET" class="flex items-center gap-2 text-xs md:text-sm">
                    <!-- Preserve pendapatan filters on submit -->
                    <input type="hidden" name="inc_month" value="{{ request('inc_month') }}">
                    <input type="hidden" name="inc_year" value="{{ request('inc_year') }}">

                    <select name="month" class="border rounded px-2 py-1 dark:bg-gray-800 dark:border-gray-700" onchange="this.form.submit()">
                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ (int)($bulanNow ?? now()->format('n')) === $m ? 'selected' : '' }}>{{ $monthNames[$m] }}</option>
                        @endfor
                    </select>
                    @php($yy = (int)($tahunNow ?? now()->format('Y')))
                    @php($minY = max(1970, $yy - 4))
                    @php($maxY = $minY + 9) {{-- total 10 tahun --}}
                    <select id="year-select" name="year" title="Dbl-klik untuk mengetik tahun" class="border rounded px-2 py-1 text-xs md:text-sm w-24 dark:bg-gray-800 dark:border-gray-700 cursor-pointer" onchange="this.form.submit()">
                        @for($y=$maxY; $y >= $minY; $y--)
                            <option value="{{ $y }}" {{ (int)($tahunNow ?? now()->format('Y')) === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button id="year-edit" type="button" class="ml-2 inline-flex items-center gap-1 text-[10px] px-1.5 py-0.5 rounded border border-emerald-300 text-emerald-700 dark:border-emerald-600 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30" title="Dbl-klik untuk mengetik tahun (klik untuk cadangan)">
                        <span class="text-xs">✎</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- SECTION: Pengeluaran Bulanan -->
        <div id="exp-section-monthly">

            @php(
                $yy = (int)($tahunNow ?? now()->format('Y'))
            )
            @php(
                $mm = (int)($bulanNow ?? now()->format('n'))
            )
            @php(
                $salaryByEmployee = $salaryByEmployee ?? ($expenseByEmployee ?? [])
            )
            @php(
                $combinedRows = []
            )
            @php(
                $salaryDate = \Illuminate\Support\Carbon::create($yy, $mm, 1)->endOfMonth()->format('Y-m-d')
            )
            @foreach($salaryByEmployee as $row)
                @php($combinedRows[] = [
                    'tanggal' => $salaryDate,
                    'jenis' => 'Gaji',
                    'keterangan' => ($row->employee ?? ($row['employee'] ?? '-')),
                    'amount' => (int)($row->salary ?? ($row['salary'] ?? 0)),
                ])
            @endforeach
            @foreach(($otherExpensesMonthly ?? []) as $exp)
                @php($combinedRows[] = [
                    'tanggal' => \Illuminate\Support\Carbon::parse($exp->tanggal)->format('Y-m-d'),
                    'jenis' => ($exp->jenis ?? 'Lainnya'),
                    'keterangan' => ($exp->deskripsi ?? '-'),
                    'amount' => (int)($exp->amount ?? 0),
                ])
            @endforeach
            @php(
                usort($combinedRows, function($a,$b){ return strcmp($a['tanggal'],$b['tanggal']); })
            )

            <div class="overflow-x-auto">
                <table class="min-w-full text-xs md:text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="py-2 pr-4">Tanggal</th>
                            <th class="py-2 pr-4">Jenis</th>
                            <th class="py-2 pr-4">Keterangan</th>
                            <th class="py-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($combinedRows as $row)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="py-2 pr-4 text-gray-800 dark:text-gray-100">{{ \Illuminate\Support\Carbon::parse($row['tanggal'])->format('d M Y') }}</td>
                                <td class="py-2 pr-4 text-gray-800 dark:text-gray-100">{{ $row['jenis'] }}</td>
                                <td class="py-2 pr-4 text-gray-800 dark:text-gray-100">{{ $row['keterangan'] }}</td>
                                <td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format($row['amount'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-2 text-center text-gray-500 dark:text-gray-400">Tidak ada pengeluaran pada bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <!-- Pemisah visual: Ringkasan -->
            <div class="relative my-4">
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 px-2 text-[11px] md:text-xs text-gray-600 dark:text-gray-300">Ringkasan Pengeluaran</span>
            </div>

            <!-- Ringkasan Bulanan -->
            <div class="mt-3 bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                <div class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-2">Ringkasan Pengeluaran Bulanan</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">Total Gaji Bulan Ini</td>
                                <td class="py-2 text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format(($monthlySalaryTotal ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">Total Pengeluaran Lain (Bulan Ini)</td>
                                <td class="py-2 text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format(($monthlyOtherExpenseTotal ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 text-gray-700 dark:text-gray-200 font-semibold">Total Pengeluaran Gabungan</td>
                                <td class="py-2 text-right font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format(($combinedMonthlyExpense ?? (($monthlySalaryTotal ?? 0)+($monthlyOtherExpenseTotal ?? 0))), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pemisah visual: Rincian Per Bulan -->
            <div class="relative my-4">
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 px-2 text-[11px] md:text-xs text-gray-600 dark:text-gray-300">Rincian Per Bulan (Pengeluaran)</span>
            </div>

            <!-- Toggle dan Rincian Pengeluaran per Bulan -->
            <div class="flex items-center justify-end mt-3">
                <button id="btn-toggle-expense" type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md border text-xs md:text-sm bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Lihat detail</span>
                    <svg id="icon-expense" class="w-4 h-4 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.108l3.71-3.878a.75.75 0 111.08 1.04l-4.24 4.43a.75.75 0 01-1.08 0l-4.24-4.43a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div id="expense-details" class="overflow-hidden transition-all duration-300 ease-out max-h-0 opacity-0 translate-y-2">
                <h3 class="text-sm md:text-base font-semibold mt-4 mb-2 text-gray-800 dark:text-gray-100">Pengeluaran per Bulan ({{ $tahunNow ?? now()->format('Y') }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs md:text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-300">
                                <th class="py-2 pr-4">Bulan</th>
                                <th class="py-2 text-right">Total Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody id="expense-month-rows">
                            @php($monthShort = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'])
                            @for($m=1;$m<=12;$m++)
                                @php($totalExp = (int)($salaryByMonth[$m] ?? 0) + (int)($expensesByMonth[$m] ?? 0))
                                <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" data-month="{{ $m }}">
                                    <td class="py-2 pr-4 text-gray-800 dark:text-gray-100">{{ $monthShort[$m] }}</td>
                                    <td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format($totalExp, 0, ',', '.') }}</td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <th class="py-2 pr-4 text-gray-800 dark:text-gray-100 text-right">Total Tahun {{ $tahunNow ?? now()->format('Y') }}</th>
                                <th class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format(($combinedYearlyExpenseTotal ?? 0), 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Panel detail per bulan (render dinamis saat klik salah satu baris di atas) -->
                <div id="expense-month-detail" class="mt-4 hidden">
                    <div class="flex items-center justify-between">
                        <h4 id="expense-month-detail-title" class="text-xs md:text-sm font-semibold text-gray-800 dark:text-gray-100">Detail Bulan</h4>
                        <button id="expense-detail-close" type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded-md border text-xs bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Tutup</button>
                    </div>
                    <div class="overflow-x-auto mt-2">
                        <table class="min-w-full text-xs md:text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 dark:text-gray-300">
                                    <th class="py-2 pr-4">Jenis</th>
                                    <th class="py-2 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="expense-month-detail-body">
                                <tr><td colspan="2" class="py-2 text-center text-gray-500 dark:text-gray-400">Pilih salah satu bulan di tabel di atas</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION: Pengeluaran Tahunan -->
        
        <!-- Pemisah antara Ringkasan Pengeluaran dan Data Pengeluaran -->
        
        
    <!-- Laporan Pendapatan - diletakkan di bawah chart -->
    <div id="report-pendapatan" class="mt-4 bg-white border border-gray-200 rounded-xl p-4 md:p-6 shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative flex items-center justify-between mb-3">
            <h2 class="text-base md:text-lg font-semibold text-gray-800 dark:text-gray-100 absolute left-1/2 -translate-x-1/2 w-full text-center pointer-events-none">Laporan Pendapatan</h2>
            <div class="ml-auto flex gap-2 items-center text-xs md:text-sm">
                @php(
                    $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']
                )
                @php($incMonth = (int)request()->query('inc_month', now()->format('n')))
                @php($incYear = (int)request()->query('inc_year', now()->format('Y')))
                <form method="GET" class="flex items-center gap-2" >
                    <!-- Preserve pengeluaran filters on submit -->
                    <input type="hidden" name="month" value="{{ request('month', now()->format('n')) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->format('Y')) }}">

                    <select name="inc_month" class="border rounded px-2 py-1 dark:bg-gray-800 dark:border-gray-700 focus:ring-2 focus:ring-emerald-400" onchange="this.form.submit()">
                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ $incMonth === $m ? 'selected' : '' }}>{{ $monthNames[$m] }}</option>
                        @endfor
                    </select>
                    @php($incMinY = max(1970, $incYear - 4))
                    @php($incMaxY = $incMinY + 9) {{-- total 10 tahun --}}
                    <select id="inc-year-select" name="inc_year" title="Dbl-klik untuk mengetik tahun" class="border rounded px-2 py-1 text-xs md:text-sm w-24 dark:bg-gray-800 dark:border-gray-700 focus:ring-2 focus:ring-emerald-400 cursor-pointer" onchange="this.form.submit()">
                        @for($y=$incMaxY; $y >= $incMinY; $y--)
                            <option value="{{ $y }}" {{ $incYear === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button id="inc-year-edit" type="button" class="ml-2 inline-flex items-center gap-1 text-[10px] px-1.5 py-0.5 rounded border border-emerald-300 text-emerald-700 dark:border-emerald-600 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30" title="Dbl-klik untuk mengetik tahun (klik untuk cadangan)">
                        <span class="text-xs">✎</span>
                    </button>
                </form>
            </div>
        </div>
        
        
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600 dark:text-gray-300">
                        <th class="py-2 pr-4">Perusahaan</th>
                        <th class="py-2 text-right">Pendapatan Net</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($revenueByCustomer ?? []) as $row)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="py-2 pr-4 text-gray-800 dark:text-gray-100">{{ $row->customer ?? '-' }}</td>
                            <td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format((int)($row->subtotal ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-3 text-center text-gray-500 dark:text-gray-400">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t border-b border-gray-300 dark:border-gray-600">
                        <th class="py-2 pr-4 text-gray-800 dark:text-gray-100 text-left">Total Bulan Ini</th>
                        <th class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format(($monthlySubtotal ?? 0), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pemisah visual: Ringkasan -->
        <div class="relative my-4">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 px-2 text-[11px] md:text-xs text-gray-600 dark:text-gray-300">Ringkasan Pendapatan</span>
        </div>

        <!-- Ringkasan (Net, PPN, Bruto) - diletakkan di bawah rincian per perusahaan -->
        
        <div class="mt-3 bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
            <div class="text-xs font-semibold text-gray-700 dark:text-gray-200">Ringkasan Pendapatan</div>
            <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">Bruto (Net + PPN)</td>
                            <td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format(($monthlyRevenue ?? 0), 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">PPN 11% (−)</td>
                            <td class="py-2 text-right text-gray-900 dark:text-gray-100">− Rp {{ number_format(($monthlyPpn ?? 0), 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-2 pr-4 text-gray-900 dark:text-gray-100 font-bold">Pendapatan (Net, tanpa PPN)</td>
                            <td class="py-2 text-right font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format(($monthlySubtotal ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pemisah visual: Rincian Per Bulan -->
        <div class="relative my-4">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 px-2 text-[11px] md:text-xs text-gray-600 dark:text-gray-300">Rincian Per Bulan (Pendapatan)</span>
        </div>

        <!-- Toggle dan Rincian Pendapatan per Bulan -->
        <div class="flex items-center justify-end mt-3">
            <button id="btn-toggle-income" type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md border text-xs md:text-sm bg-transparent text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <span>Lihat detail</span>
                <svg id="icon-income" class="w-4 h-4 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.108l3.71-3.878a.75.75 0 111.08 1.04l-4.24 4.43a.75.75 0 01-1.08 0l-4.24-4.43a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
            </button>
        </div>
        <div id="income-details" class="overflow-hidden transition-all duration-300 ease-out max-h-0 opacity-0 translate-y-2">
            <h3 class="text-sm md:text-base font-semibold mt-4 mb-2 text-gray-800 dark:text-gray-100">Pendapatan per Bulan ({{ $incYear ?? now()->format('Y') }})</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs md:text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 dark:text-gray-300">
                            <th class="py-2 pr-4">Bulan</th>
                            <th class="py-2 text-right">Total Pendapatan (Net)</th>
                        </tr>
                    </thead>
                    <tbody id="income-month-rows">
                        @php($monthShort = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'])
                        @for($m=1;$m<=12;$m++)
                            <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" data-month="{{ $m }}">
                                <td class="py-2 pr-4 text-gray-800 dark:text-gray-100">{{ $monthShort[$m] }}</td>
                                <td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format((int)($revenueNetByMonth[$m] ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-4 text-gray-800 dark:text-gray-100 text-right">Total Tahun {{ $incYear ?? now()->format('Y') }}</th>
                            <th class="py-2 text-right text-gray-900 dark:text-gray-100">Rp {{ number_format(array_sum($revenueNetByMonth ?? []), 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Panel detail per bulan (render dinamis saat klik salah satu baris di atas) -->
            <div id="income-month-detail" class="mt-4 hidden">
                <div class="flex items-center justify-between">
                    <h4 id="income-month-detail-title" class="text-xs md:text-sm font-semibold text-gray-800 dark:text-gray-100">Detail Bulan</h4>
                    <button id="income-detail-close" type="button" class="px-2 py-1 text-xs md:text-sm rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Tutup</button>
                </div>
                <div class="overflow-x-auto mt-2">
                    <table class="min-w-full text-xs md:text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-300">
                                <th class="py-2 pr-4">Perusahaan</th>
                                <th class="py-2 text-right">Pendapatan Net</th>
                            </tr>
                        </thead>
                        <tbody id="income-month-detail-body">
                            <tr><td colspan="2" class="py-2 text-center text-gray-500 dark:text-gray-400">Pilih salah satu bulan di tabel di atas</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pendapatan (paling bawah) -->
    
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

    // Build labels and datasets from backend data (salaryByMonth, expensesByMonth)
    const monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const salaryMap = @json($salaryByMonth ?? []);
    const otherMap = @json($expensesByMonth ?? []);
    const revenueMap = @json($revenueNetByMonth ?? []);
    const dataGaji = Array.from({length: 12}, (_, i) => Number(salaryMap[i+1] ?? 0));
    const dataLain = Array.from({length: 12}, (_, i) => Number(otherMap[i+1] ?? 0));
    const dataPendapatan = Array.from({length: 12}, (_, i) => Number(revenueMap[i+1] ?? 0));
    const dataPengeluaran = Array.from({length: 12}, (_, i) => (Number(salaryMap[i+1] ?? 0) + Number(otherMap[i+1] ?? 0)));

    const fmtIDR = (v) => {
        try { return new Intl.NumberFormat('id-ID').format(v || 0); } catch { return (v||0).toLocaleString('id-ID'); }
    };

    const gradientBlue = ctx.createLinearGradient(0, 0, 0, canvasEl.height);
    gradientBlue.addColorStop(0, 'rgba(59,130,246,0.45)');
    gradientBlue.addColorStop(1, 'rgba(59,130,246,0.05)');
    const gradientRed = ctx.createLinearGradient(0, 0, 0, canvasEl.height);
    gradientRed.addColorStop(0, 'rgba(239,68,68,0.45)');
    gradientRed.addColorStop(1, 'rgba(239,68,68,0.05)');
    // Single red gradient used for total expenses

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Pendapatan (Net)',
                    data: dataPendapatan,
                    backgroundColor: gradientBlue,
                    borderColor: 'rgba(59,130,246,1)', // biru
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                    tension: 0.35,
                },
                {
                    label: 'Pengeluaran',
                    data: dataPengeluaran,
                    backgroundColor: gradientRed,
                    borderColor: 'rgba(239,68,68,1)', // merah
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                    tension: 0.35,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: {
                        color: colors.text,
                        font: { size: window.innerWidth < 768 ? 12 : 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx){
                            const val = ctx.parsed.y || 0;
                            return `${ctx.dataset.label}: Rp ${fmtIDR(val)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: colors.text,
                        callback: (v) => `Rp ${fmtIDR(v)}`,
                        font: { size: window.innerWidth < 768 ? 10 : 12 }
                    },
                    grid: { color: colors.grid }
                },
                x: {
                    ticks: { color: colors.text, font: { size: window.innerWidth < 768 ? 10 : 12 } },
                    grid: { color: colors.grid }
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
    
    // Klik kartu statistik untuk toggle laporan
    (function statCardToggle() {
        const cardPendapatan = document.getElementById('stat-pendapatan');
        const cardPengeluaran = document.getElementById('stat-pengeluaran');
        const reportPendapatan = document.getElementById('report-pendapatan');
        const reportPengeluaran = document.getElementById('report-pengeluaran');

        if (!cardPendapatan || !cardPengeluaran || !reportPendapatan || !reportPengeluaran) return;

        function setActive(which) {
            const ringActivePend = ['ring','ring-emerald-400'];
            const ringActivePeng = ['ring','ring-rose-400'];

            if (which === 'pendapatan') {
                reportPendapatan.classList.remove('hidden');
                reportPengeluaran.classList.add('hidden');
                cardPendapatan.classList.add(...ringActivePend);
                cardPengeluaran.classList.remove(...ringActivePeng);
            } else {
                reportPendapatan.classList.add('hidden');
                reportPengeluaran.classList.remove('hidden');
                cardPengeluaran.classList.add(...ringActivePeng);
                cardPendapatan.classList.remove(...ringActivePend);
            }

            // Sesuaikan tinggi container chart bila perlu
            window.dispatchEvent(new Event('forcechartresize'));
        }

        ['click','keydown'].forEach(evt => {
            cardPendapatan.addEventListener(evt, (e) => {
                if (evt === 'click' || (evt === 'keydown' && (e.key === 'Enter' || e.key === ' '))) setActive('pendapatan');
            });
            cardPengeluaran.addEventListener(evt, (e) => {
                if (evt === 'click' || (evt === 'keydown' && (e.key === 'Enter' || e.key === ' '))) setActive('pengeluaran');
            });
        });

        // default aktif: pendapatan
        setActive('pendapatan');
    })();

    // Toggle Bulanan/Tahunan di bagian Pengeluaran
    (function expenseTabs() {
        const btnMonthly = document.getElementById('btn-exp-monthly');
        const btnYearly = document.getElementById('btn-exp-yearly');
        const secMonthly = document.getElementById('exp-section-monthly');
        const secYearly = document.getElementById('exp-section-yearly');
        if (!btnMonthly || !btnYearly || !secMonthly || !secYearly) return;

        function setTab(tab) {
            const active = ['bg-rose-600','text-white','border-rose-600'];
            const inactive = ['bg-transparent','text-gray-700','dark:text-gray-200','border-gray-300'];
            if (tab === 'monthly') {
                secMonthly.classList.remove('hidden');
                secYearly.classList.add('hidden');
                btnMonthly.classList.add(...active); btnMonthly.classList.remove(...inactive);
                btnYearly.classList.add(...inactive); btnYearly.classList.remove(...active);
            } else {
                secMonthly.classList.add('hidden');
                secYearly.classList.remove('hidden');
                btnYearly.classList.add(...active); btnYearly.classList.remove(...inactive);
                btnMonthly.classList.add(...inactive); btnMonthly.classList.remove(...active);
            }
            window.dispatchEvent(new Event('forcechartresize'));
        }

        btnMonthly.addEventListener('click', () => setTab('monthly'));
        btnYearly.addEventListener('click', () => setTab('yearly'));
        setTab('monthly');
    })();

    // Toggle detail tahunan dihapus — panel kini selalu terbuka

    // Toggle Pendapatan (income-details) dengan animasi halus
    (function toggleIncomeDetails(){
        const btn = document.getElementById('btn-toggle-income');
        const panel = document.getElementById('income-details');
        const icon = document.getElementById('icon-income');
        if (!btn || !panel) return;

        const LABEL_SHOW = 'Lihat detail';
        const LABEL_HIDE = 'Sembunyikan detail';
        let open = false;

        function openPanel(){
            panel.style.maxHeight = panel.scrollHeight + 'px';
            panel.style.opacity = '1';
            panel.style.transform = 'translateY(0)';
            if (icon) icon.style.transform = 'rotate(180deg)';
            if (btn.firstElementChild) btn.firstElementChild.textContent = LABEL_HIDE;
            window.dispatchEvent(new Event('forcechartresize'));
        }
        function closePanel(){
            panel.style.maxHeight = panel.scrollHeight + 'px';
            panel.getBoundingClientRect();
            panel.style.maxHeight = '0px';
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(0.5rem)';
            if (icon) icon.style.transform = 'rotate(0deg)';
            if (btn.firstElementChild) btn.firstElementChild.textContent = LABEL_SHOW;
            window.dispatchEvent(new Event('forcechartresize'));
        }
        panel.addEventListener('transitionend', (e) => {
            if (e.propertyName === 'max-height' && open) panel.style.maxHeight = 'none';
        });
        function toggle(){
            if (open){ open=false; closePanel(); } else { open=true; openPanel(); }
        }
        // init closed matches initial classes
        panel.style.maxHeight = '0px';
        panel.style.opacity = '0';
        panel.style.transform = 'translateY(0.5rem)';
        if (btn.firstElementChild) btn.firstElementChild.textContent = LABEL_SHOW;
        btn.addEventListener('click', toggle);
        btn.addEventListener('keydown', (e)=>{ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); toggle(); }});
        window.addEventListener('resize', ()=>{ if(open && panel.style.maxHeight!=='none'){ panel.style.maxHeight = panel.scrollHeight+'px'; }});

        // expose opener for month click
        window.__openIncomePanel = () => { if (!open) { open = true; openPanel(); } };
    })();

    // Close button for month detail panel
    (function incomeDetailClose(){
        const closeBtn = document.getElementById('income-detail-close');
        const wrap = document.getElementById('income-month-detail');
        if (!closeBtn || !wrap) return;
        const hide = () => wrap.classList.add('hidden');
        closeBtn.addEventListener('click', hide);
        closeBtn.addEventListener('keydown', (e)=>{ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); hide(); }});
    })();

    // Interaksi tabel bulan (Pendapatan): klik bulan => tampilkan detail per perusahaan
    (function incomeMonthClick(){
        const data = @json($revenueByCustomerByMonth ?? []);
        const rowsWrap = document.getElementById('income-month-rows');
        const detailWrap = document.getElementById('income-month-detail');
        const detailBody = document.getElementById('income-month-detail-body');
        const detailTitle = document.getElementById('income-month-detail-title');
        const panel = document.getElementById('income-details');
        if (!rowsWrap || !detailWrap) return;

        const monthNames = {1:'Januari',2:'Februari',3:'Maret',4:'April',5:'Mei',6:'Juni',7:'Juli',8:'Agustus',9:'September',10:'Oktober',11:'November',12:'Desember'};
        const fmt = new Intl.NumberFormat('id-ID');

        function renderDetail(month){
            const items = data[String(month)] || data[month] || [];
            detailTitle.textContent = `Detail Pendapatan Bulan ${monthNames[month]}`;
            if (!items || items.length === 0) {
                detailBody.innerHTML = '<tr><td colspan="2" class="py-2 text-center text-gray-500 dark:text-gray-400">Tidak ada data</td></tr>';
                detailWrap.classList.remove('hidden');
                return;
            }
            let html = '';
            for (const it of items) {
                const cust = (it.customer ?? '-');
                const val = Number(it.subtotal ?? 0);
                html += `<tr class=\"border-t border-gray-200 dark:border-gray-700\">`+
                        `<td class=\"py-2 pr-4 text-gray-800 dark:text-gray-100\">${cust}</td>`+
                        `<td class=\"py-2 text-right text-gray-900 dark:text-gray-100\">Rp ${fmt.format(val)}</td>`+
                        `</tr>`;
            }
            detailBody.innerHTML = html;
            detailWrap.classList.remove('hidden');
        }

        rowsWrap.querySelectorAll('tr[data-month]')?.forEach(tr => {
            tr.addEventListener('click', () => {
                const m = parseInt(tr.getAttribute('data-month') || '0', 10);
                // pastikan panel pendapatan terbuka
                if (typeof window.__openIncomePanel === 'function') {
                    window.__openIncomePanel();
                } else if (panel) {
                    // fallback buka panel jika helper belum tersedia
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                    panel.style.opacity = '1';
                    panel.style.transform = 'translateY(0)';
                }
                renderDetail(m);
                // scroll into view setelah animasi
                setTimeout(() => detailWrap.scrollIntoView({behavior:'smooth', block:'start'}), 120);
            });
        });
    })();

    // Toggle Pengeluaran (expense-details) dengan animasi halus
    (function toggleExpenseDetails(){
        const btn = document.getElementById('btn-toggle-expense');
        const panel = document.getElementById('expense-details');
        const icon = document.getElementById('icon-expense');
        if (!btn || !panel) return;

        const LABEL_SHOW = 'Lihat detail';
        const LABEL_HIDE = 'Sembunyikan detail';
        let open = false;

        function openPanel(){
            panel.style.maxHeight = panel.scrollHeight + 'px';
            panel.style.opacity = '1';
            panel.style.transform = 'translateY(0)';
            if (icon) icon.style.transform = 'rotate(180deg)';
            if (btn.firstElementChild) btn.firstElementChild.textContent = LABEL_HIDE;
            window.dispatchEvent(new Event('forcechartresize'));
        }
        function closePanel(){
            panel.style.maxHeight = panel.scrollHeight + 'px';
            panel.getBoundingClientRect();
            panel.style.maxHeight = '0px';
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(0.5rem)';
            if (icon) icon.style.transform = 'rotate(0deg)';
            if (btn.firstElementChild) btn.firstElementChild.textContent = LABEL_SHOW;
            window.dispatchEvent(new Event('forcechartresize'));
        }
        panel.addEventListener('transitionend', (e) => {
            if (e.propertyName === 'max-height' && open) panel.style.maxHeight = 'none';
        });
        function toggle(){ if (open){ open=false; closePanel(); } else { open=true; openPanel(); } }
        // init closed matches initial classes
        panel.style.maxHeight = '0px';
        panel.style.opacity = '0';
        panel.style.transform = 'translateY(0.5rem)';
        if (btn.firstElementChild) btn.firstElementChild.textContent = LABEL_SHOW;
        btn.addEventListener('click', toggle);
        btn.addEventListener('keydown', (e)=>{ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); toggle(); }});
        window.addEventListener('resize', ()=>{ if(open && panel.style.maxHeight!=='none'){ panel.style.maxHeight = panel.scrollHeight+'px'; }});

        // expose opener for month click
        window.__openExpensePanel = () => { if (!open) { open = true; openPanel(); } };
    })();

    // Close button for expense month detail panel
    (function expenseDetailClose(){
        const closeBtn = document.getElementById('expense-detail-close');
        const wrap = document.getElementById('expense-month-detail');
        if (!closeBtn || !wrap) return;
        const hide = () => wrap.classList.add('hidden');
        closeBtn.addEventListener('click', hide);
        closeBtn.addEventListener('keydown', (e)=>{ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); hide(); }});
    })();

    // Interaksi tabel bulan (Pengeluaran): klik bulan => tampilkan Gaji vs Pengeluaran Lain
    (function expenseMonthClick(){
        const rowsWrap = document.getElementById('expense-month-rows');
        const detailWrap = document.getElementById('expense-month-detail');
        const detailBody = document.getElementById('expense-month-detail-body');
        const detailTitle = document.getElementById('expense-month-detail-title');
        const panel = document.getElementById('expense-details');
        if (!rowsWrap || !detailWrap) return;

        const monthNames = {1:'Januari',2:'Februari',3:'Maret',4:'April',5:'Mei',6:'Juni',7:'Juli',8:'Agustus',9:'September',10:'Oktober',11:'November',12:'Desember'};
        const fmt = new Intl.NumberFormat('id-ID');
        const salaryMap = @json($salaryByMonth ?? []);
        const otherMap = @json($expensesByMonth ?? []);

        function renderDetail(month){
            const gaji = Number(salaryMap[String(month)] ?? salaryMap[month] ?? 0);
            const lain = Number(otherMap[String(month)] ?? otherMap[month] ?? 0);
            detailTitle.textContent = `Detail Pengeluaran Bulan ${monthNames[month]}`;
            let html = '';
            html += `<tr class="border-t border-gray-200 dark:border-gray-700">`+
                    `<td class="py-2 pr-4 text-gray-800 dark:text-gray-100">Gaji</td>`+
                    `<td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp ${fmt.format(gaji)}</td>`+
                    `</tr>`;
            html += `<tr class="border-t border-gray-200 dark:border-gray-700">`+
                    `<td class="py-2 pr-4 text-gray-800 dark:text-gray-100">Pengeluaran Lain</td>`+
                    `<td class="py-2 text-right text-gray-900 dark:text-gray-100">Rp ${fmt.format(lain)}</td>`+
                    `</tr>`;
            html += `<tr class="border-t border-gray-200 dark:border-gray-700">`+
                    `<th class="py-2 pr-4 text-right text-gray-800 dark:text-gray-100">Total</th>`+
                    `<th class="py-2 text-right text-gray-900 dark:text-gray-100">Rp ${fmt.format(gaji+lain)}</th>`+
                    `</tr>`;
            detailBody.innerHTML = html;
            detailWrap.classList.remove('hidden');
        }

        rowsWrap.querySelectorAll('tr[data-month]')?.forEach(tr => {
            tr.addEventListener('click', () => {
                const m = parseInt(tr.getAttribute('data-month') || '0', 10);
                // pastikan panel pengeluaran terbuka
                if (typeof window.__openExpensePanel === 'function') {
                    window.__openExpensePanel();
                } else if (panel) {
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                    panel.style.opacity = '1';
                    panel.style.transform = 'translateY(0)';
                }
                renderDetail(m);
                setTimeout(() => detailWrap.scrollIntoView({behavior:'smooth', block:'start'}), 120);
            });
        });
    })();

    // Modal Tambah Pengeluaran
    (function addExpenseModal(){
        const btnOpen = document.getElementById('btn-add-expense');
        const modal = document.getElementById('modal-add-expense');
        const btnClose = document.getElementById('close-add-expense');
        const btnCancel = document.getElementById('cancel-add-expense');
        if (!btnOpen || !modal) return;

        const open = () => modal.classList.remove('hidden');
        const close = () => modal.classList.add('hidden');

        btnOpen.addEventListener('click', open);
        btnClose?.addEventListener('click', close);
        btnCancel?.addEventListener('click', close);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) close();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') close();
        });
    })();

    // Dbl-klik tahun: izinkan ketik manual tahun khusus lalu submit
    (function customYearInput(){
        function openPromptFor(select){
            if (!select) return;
            const current = parseInt(select.value || '0', 10) || new Date().getFullYear();
            const input = prompt('Masukkan tahun (1970 - 2100):', String(current));
            if (input === null) return; // cancelled
            const val = parseInt((input || '').trim(), 10);
            if (!Number.isInteger(val) || val < 1970 || val > 2100) {
                alert('Tahun tidak valid. Gunakan rentang 1970 - 2100.');
                return;
            }
            // Tambahkan option jika belum ada
            let opt = Array.from(select.options).find(o => parseInt(o.value, 10) === val);
            if (!opt) {
                opt = document.createElement('option');
                opt.value = String(val);
                opt.textContent = String(val);
                select.appendChild(opt);
            }
            select.value = String(val);
            // Submit form terdekat
            const form = select.closest('form');
            if (form) form.submit();
        }

        function attachSelect(select){
            if (!select) return;
            select.addEventListener('dblclick', () => openPromptFor(select));
        }
        function attachButton(button, select){
            if (!button || !select) return;
            button.addEventListener('dblclick', (e) => { e.preventDefault(); openPromptFor(select); });
            button.addEventListener('click', (e) => { e.preventDefault(); openPromptFor(select); }); // fallback single click
        }

        const expSelect = document.getElementById('year-select');
        const incSelect = document.getElementById('inc-year-select');
        const expBtn = document.getElementById('year-edit');
        const incBtn = document.getElementById('inc-year-edit');
        attachSelect(expSelect);
        attachSelect(incSelect);
        attachButton(expBtn, expSelect);
        attachButton(incBtn, incSelect);
    })();
</script>
@endpush
