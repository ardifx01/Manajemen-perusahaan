@extends('layouts.app')

@section('title', 'Manajemen Gaji Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 dark:bg-white/5 dark:border-white/10">
            <div class="flex items-center">
                <div class="bg-green-500 text-white p-2 rounded-full mr-3 dark:bg-green-500/25 dark:text-green-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Gaji Dibayar</p>
                    <p class="text-xl font-bold text-green-600 dark:text-green-300">Rp <span id="statTotalDibayar">{{ number_format($totalGajiDibayar ?? 0, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4 dark:bg-white/5 dark:border-white/10">
            <div class="flex items-center">
                <div class="bg-red-500 text-white p-2 rounded-full mr-3 dark:bg-red-500/25 dark:text-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Gaji Belum Dibayar</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-300">Rp <span id="statBelumDibayar">{{ number_format($totalGajiBelumDibayar ?? 0, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 dark:bg-white/5 dark:border-white/10">
            <div class="flex items-center">
                <div class="bg-blue-500 text-white p-2 rounded-full mr-3 dark:bg-blue-500/25 dark:text-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Karyawan Dibayar</p>
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-300"><span id="statKaryawanDibayar">{{ $jumlahKaryawanDibayar ?? 0 }}</span></p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 dark:bg-white/5 dark:border-white/10">
            <div class="flex items-center">
                <div class="bg-purple-500 text-white p-2 rounded-full mr-3 dark:bg-purple-500/25 dark:text-purple-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Rata-rata Gaji</p>
                    <p class="text-xl font-bold text-purple-600 dark:text-purple-300">Rp <span id="statRataRata">{{ number_format($rataRataGaji ?? 0, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Header dan Tombol Aksi -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0 mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Gaji Karyawan</h1>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <input type="text" id="searchKaryawan" placeholder="Cari nama karyawan..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800/60 dark:border-white/10 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-500/40" oninput="filterKaryawanTable()">
            <!-- Filter Bulan -->
            <select id="filterBulan" class="appearance-none no-arrow px-3 py-2 border border-gray-300 rounded-md dark:bg-slate-800/60 dark:border-white/10 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500/40" onchange="filterKaryawanTable()">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                @endfor
            </select>
            <!-- Filter Tahun (hidden untuk filtering, tapi tetap ada) -->
            <select id="filterTahun" class="hidden" onchange="filterKaryawanTable()">
                <option value="">Semua Tahun</option>
                @for($i = 2020; $i <= 2035; $i++)
                    <option value="{{ $i }}" {{ $i == now()->format('Y') ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <!-- Link Pilih Tahun -->
            <button type="button" onclick="openYearModal()" 
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-md hover:bg-indigo-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-700 dark:hover:bg-indigo-900/50">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span id="yearButtonText">Pilih Tahun ({{ now()->format('Y') }})</span>
            </button>
            <button onclick="openModal('tambahModal')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Tambah Gaji</span>
            </button>
        </div>
    </div>

    <!-- Peringatan: akumulasi potongan -->
    <div class="rounded-lg border border-amber-200 bg-amber-50 text-amber-800 px-3 py-2 text-sm mb-3 dark:bg-amber-900/20 dark:border-amber-700 dark:text-amber-300">
        <span class="font-semibold">Peringatan:</span>
        Potongan pajak, Potongan BPJS, dan potongan lainnya sudah digabungkan. Nilai totalnya ditampilkan pada kolom <span class="font-semibold">Potongan</span>, sedangkan kolom <span class="font-semibold">Total Gaji</span> merupakan gaji bersih setelah seluruh potongan tersebut.
    </div>

    <!-- Tabel Gaji -->
    <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-slate-900/40 dark:border dark:border-white/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Karyawan</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tunjangan</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bonus</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lembur</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Potongan</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gaji</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-transparent dark:divide-white/10">
                    @forelse($salaries ?? [] as $salary)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-3 py-2 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $salary->employee->nama_karyawan }}</div>
                            </div>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ DateTime::createFromFormat('!m', $salary->bulan)->format('F') }} {{ $salary->tahun }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">Rp {{ number_format($salary->tunjangan ?? 0, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">Rp {{ number_format($salary->bonus ?? 0, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">Rp {{ number_format($salary->lembur ?? 0, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-red-600 dark:text-red-300">Rp {{ number_format($salary->total_potongan, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $salary->status_pembayaran === 'dibayar' ? 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-200' }}">
                                {{ $salary->status_pembayaran === 'dibayar' ? 'Dibayar' : 'Belum Dibayar' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                            <x-table.action-buttons 
                                onEdit="editSalary({{ json_encode($salary) }})"
                                deleteAction="{{ route('salary.destroy', $salary) }}"
                                confirmText="Yakin ingin menghapus data gaji ini?"
                                :useMenu="false"
                            />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">Belum ada data gaji</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast peringatan duplikasi (non-blokir) -->
<div id="toast-duplicate" class="fixed top-4 right-4 z-[60] hidden">
    <div class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 shadow-lg dark:bg-amber-900/20 dark:border-amber-700 dark:text-amber-300">
        <svg class="w-5 h-5 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" />
        </svg>
        <div class="text-sm">
            <span class="font-semibold">Peringatan:</span>
            Data gaji untuk karyawan dan bulan ini sudah ada. Silakan periksa kembali atau gunakan menu edit jika ingin mengubah.
        </div>
    </div>
    <style>
        #toast-duplicate.show { display:block; animation: fadeOut 3.2s forwards; }
        #toast-duplicate { display:none; }
        @keyframes fadeOut { 0%{opacity:1} 80%{opacity:1} 100%{opacity:0; display:none} }
    </style>
</div>

<!-- Modal Peringatan Duplikasi (tengah, dengan tombol Kembali) -->
<div id="duplicateCenterModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[70]">
    <div class="bg-white rounded-lg shadow-2xl w-11/12 sm:w-[460px] p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" />
            </svg>
            <div>
                <h4 class="text-base font-semibold text-gray-900">Duplikasi Data Gaji</h4>
                <p class="mt-1 text-sm text-gray-600">
                    Gaji untuk karyawan dan periode yang dipilih sudah tercatat. Silakan periksa kembali atau gunakan menu edit.
                </p>
            </div>
        </div>
        <div class="mt-5 text-right">
            <button type="button" onclick="closeDuplicateCenterModal()" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300">Kembali</button>
        </div>
    </div>
    <style>
        #duplicateCenterModal { display: none; }
        #duplicateCenterModal.show { display: flex; }
    </style>
    <script>
        function openDuplicateCenterModal(){ document.getElementById('duplicateCenterModal').classList.add('show'); }
        function closeDuplicateCenterModal(){ document.getElementById('duplicateCenterModal').classList.remove('show'); }
    </script>
</div>

<!-- Modal Generate Payroll -->
<div id="payrollModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Generate Payroll</h3>
            <button onclick="closeModal('payrollModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('salary.generate-payroll') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @for($i = 2020; $i <= 2030; $i++)
                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('payrollModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    Generate
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Gaji -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Tambah Data Gaji</h3>
            <button onclick="closeModal('tambahModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('salary.store') }}" class="space-y-4" onsubmit="return prepareFormSubmission(this)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                    <select name="employee_id" id="employee_select" required onchange="autoFillEmployeeData()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees ?? [] as $employee)
                        <option value="{{ $employee->id }}" data-gaji-pokok="{{ $employee->gaji_pokok ?? 0 }}" data-nama="{{ $employee->nama_karyawan }}" data-posisi="{{ $employee->posisi }}">
                            {{ $employee->nama_karyawan }} - {{ $employee->posisi }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @for($i = 2020; $i <= 2030; $i++)
                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Pokok</label>
                    <input type="text" id="gaji_pokok_display" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-100 text-gray-600 cursor-not-allowed">
                    <input type="hidden" name="gaji_pokok" id="gaji_pokok_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tunjangan</label>
                    <input type="text" id="tunjangan_display" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                    <input type="hidden" name="tunjangan" id="tunjangan_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bonus</label>
                    <input type="text" id="bonus_display" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                    <input type="hidden" name="bonus" id="bonus_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lembur</label>
                    <input type="text" id="lembur_display" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                    <input type="hidden" name="lembur" id="lembur_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Potongan Pajak</label>
                    <input type="text" id="potongan_pajak_display" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                    <input type="hidden" name="potongan_pajak" id="potongan_pajak_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Potongan BPJS</label>
                    <input type="text" id="potongan_bpjs_display" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                    <input type="hidden" name="potongan_bpjs" id="potongan_bpjs_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Potongan Lain</label>
                    <input type="text" id="potongan_lain_display" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                    <input type="hidden" name="potongan_lain" id="potongan_lain_value">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                    <select name="status_pembayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="belum_dibayar">Belum Dibayar</option>
                        <option value="dibayar">Dibayar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('tambahModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Gaji -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Edit Data Gaji</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" id="editForm" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                    <select name="employee_id" id="edit_employee_select" required disabled class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 text-gray-600 cursor-not-allowed">
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees ?? [] as $employee)
                        <option value="{{ $employee->id }}" data-gaji-pokok="{{ $employee->gaji_pokok ?? 0 }}" data-nama="{{ $employee->nama_karyawan }}" data-posisi="{{ $employee->posisi }}">
                            {{ $employee->nama_karyawan }} - {{ $employee->posisi }}
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="employee_id" id="edit_employee_id_hidden">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" id="edit_bulan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" id="edit_tahun" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for($i = 2020; $i <= 2030; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Pokok</label>
                    <input type="text" name="gaji_pokok" id="edit_gaji_pokok" required min="0" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 text-gray-600 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tunjangan</label>
                    <input type="text" name="tunjangan" id="edit_tunjangan" min="0" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bonus</label>
                    <input type="text" name="bonus" id="edit_bonus" min="0" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lembur</label>
                    <input type="text" name="lembur" id="edit_lembur" min="0" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Potongan Pajak</label>
                    <input type="text" name="potongan_pajak" id="edit_potongan_pajak" min="0" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Potongan BPJS</label>
                    <input type="text" name="potongan_bpjs" id="edit_potongan_bpjs" min="0" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Potongan Lain</label>
                    <input type="text" name="potongan_lain" id="edit_potongan_lain" min="0" class="currency-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" oninput="formatCurrencyInput(this)" onblur="formatCurrencyInput(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                    <select name="status_pembayaran" id="edit_status_pembayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="belum_dibayar">Belum Dibayar</option>
                        <option value="dibayar">Dibayar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" id="edit_tanggal_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editSalary(salaryData) {
    console.log('[v0] Opening edit modal with data:', salaryData);
    
    // Set form action URL
    const editForm = document.getElementById('editForm');
    editForm.action = `/salary/${salaryData.id}`;
    
    // Populate form fields with formatted currency values
    document.getElementById('edit_employee_select').value = salaryData.employee_id;
    document.getElementById('edit_employee_id_hidden').value = salaryData.employee_id;
    document.getElementById('edit_bulan').value = salaryData.bulan;
    document.getElementById('edit_tahun').value = salaryData.tahun;
    
    // Format currency fields to remove decimals and display as whole numbers
    const gajiPokokValue = Math.round(parseFloat(salaryData.gaji_pokok) || 0);
    document.getElementById('edit_gaji_pokok').value = gajiPokokValue;
    
    document.getElementById('edit_tunjangan').value = Math.round(parseFloat(salaryData.tunjangan) || 0);
    document.getElementById('edit_bonus').value = Math.round(parseFloat(salaryData.bonus) || 0);
    document.getElementById('edit_lembur').value = Math.round(parseFloat(salaryData.lembur) || 0);
    document.getElementById('edit_potongan_pajak').value = Math.round(parseFloat(salaryData.potongan_pajak) || 0);
    document.getElementById('edit_potongan_bpjs').value = Math.round(parseFloat(salaryData.potongan_bpjs) || 0);
    document.getElementById('edit_potongan_lain').value = Math.round(parseFloat(salaryData.potongan_lain) || 0);
    
    document.getElementById('edit_status_pembayaran').value = salaryData.status_pembayaran;
    document.getElementById('edit_tanggal_bayar').value = salaryData.tanggal_bayar || '';
    document.getElementById('edit_keterangan').value = salaryData.keterangan || '';
    
    // Open modal
    openModal('editModal');
}

function autoFillEmployeeData() {
    const employeeSelect = document.getElementById('employee_select');
    const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
    const gajiPokokDisplay = document.getElementById('gaji_pokok_display');
    const gajiPokokValue = document.getElementById('gaji_pokok_value');

    if (selectedOption.value) {
        // Get raw gaji pokok value directly from data attribute
        const rawGajiPokok = selectedOption.getAttribute('data-gaji-pokok');
        console.log('[v0] Raw gaji pokok from employee:', rawGajiPokok);
        
        if (rawGajiPokok && rawGajiPokok !== '0') {
            // Convert to integer to ensure no decimal issues
            const gajiPokokInt = parseInt(rawGajiPokok);
            console.log('[v0] Converted gaji pokok:', gajiPokokInt);
            
            // Format for display using Indonesian locale
            const formattedGaji = 'Rp ' + gajiPokokInt.toLocaleString('id-ID');
            gajiPokokDisplay.value = formattedGaji;
            gajiPokokValue.value = gajiPokokInt;
            
            console.log('[v0] Final formatted gaji:', formattedGaji);
        } else {
            gajiPokokDisplay.value = '';
            gajiPokokValue.value = '';
        }
        
        console.log('[v0] Auto-filled employee data:', {
            nama: selectedOption.getAttribute('data-nama'),
            posisi: selectedOption.getAttribute('data-posisi'),
            gajiPokok: rawGajiPokok
        });
    } else {
        // Clear form if no employee selected
        gajiPokokDisplay.value = '';
        gajiPokokValue.value = '';
    }
}

function formatRupiah(number) {
    if (!number) return '';
    const num = parseInt(number.toString().replace(/[^\d]/g, ''));
    if (isNaN(num) || num === 0) return '';
    return 'Rp ' + num.toLocaleString('id-ID');
}

function formatCurrencyInput(input) {
    // Get the raw numeric value, removing all non-digits
    let rawValue = input.value.replace(/[^\d]/g, '');
    
    // If empty, clear both fields
    if (!rawValue) {
        input.value = '';
        updateHiddenField(input, '');
        return;
    }
    
    // Convert to number and format
    const numericValue = parseInt(rawValue);
    if (!isNaN(numericValue)) {
        // Format for display
        input.value = formatRupiah(numericValue);
        // Store raw numeric value in hidden field
        updateHiddenField(input, numericValue.toString());
    }
}

function updateHiddenField(displayInput, value) {
    const fieldName = displayInput.id.replace('_display', '_value');
    const hiddenField = document.getElementById(fieldName);
    if (hiddenField) {
        hiddenField.value = value;
    }
}

function prepareFormSubmission(form) {
    console.log('[v0] Preparing form submission...');
    
    // Cek duplikasi: 1 karyawan hanya boleh 1 gaji per bulan & tahun
    try {
        const employeeId = parseInt(form.querySelector('#employee_select')?.value);
        const bulan = parseInt(form.querySelector('[name="bulan"]')?.value);
        const tahun = parseInt(form.querySelector('[name="tahun"]')?.value);
        if (!isNaN(employeeId) && !isNaN(bulan) && !isNaN(tahun)) {
            const exists = (existingSalariesData || []).some(s => parseInt(s.employee_id) === employeeId && parseInt(s.bulan) === bulan && parseInt(s.tahun) === tahun);
            if (exists) {
                // Tampilkan modal tengah dan batalkan submit
                openDuplicateCenterModal();
                return false;
            }
        }
    } catch (e) { console.warn('Duplicate check error:', e); }

    // Normalisasi angka sebelum submit
    const currencyFields = ['tunjangan', 'bonus', 'lembur', 'potongan_pajak', 'potongan_bpjs', 'potongan_lain'];
    currencyFields.forEach(field => {
        const displayField = form.querySelector(`[name="${field}_display"]`);
        const hiddenField = form.querySelector(`[name="${field}"]`);
        if (displayField && hiddenField) {
            const numericValue = displayField.value.replace(/[^\d]/g, '') || '0';
            hiddenField.value = numericValue;
        }
    });

    return true;
}

// Fitur pencarian nama karyawan
function filterKaryawanTable() {
    const input = document.getElementById('searchKaryawan').value.toLowerCase();
    const filterBulan = document.getElementById('filterBulan').value;
    const filterTahun = document.getElementById('filterTahun').value;
    const rows = document.querySelectorAll('table tbody tr');

    // 1) Tampilkan/sembunyikan baris sesuai pencarian + filter bulan/tahun
    rows.forEach(row => {
        const namaCell = row.querySelector('td');
        if (!namaCell) return;
        const nama = namaCell.textContent.toLowerCase();

        const periodeCell = row.querySelectorAll('td')[1];
        let show = nama.includes(input);

        if (periodeCell) {
            const periodeText = periodeCell.textContent.trim();
            if (filterBulan) {
                const bulanNama = periodeText.split(' ')[0];
                const bulanIndex = new Date(Date.parse(bulanNama + " 1, 2020")).getMonth() + 1;
                if (parseInt(filterBulan) !== bulanIndex) show = false;
            }
            if (filterTahun) {
                const tahun = periodeText.split(' ')[1];
                if (tahun !== filterTahun) show = false;
            }
        }
        row.style.display = show ? '' : 'none';
    });

    // 2) Hitung ulang kartu statistik berbasis filter bulan/tahun SAJA (abaikan pencarian nama)
    let totalDibayar = 0;
    let totalBelumDibayar = 0;
    let countDibayar = 0;
    let totalGajiSemua = 0;
    let countSemua = 0;

    rows.forEach(row => {
        const tds = row.querySelectorAll('td');
        if (tds.length < 9) return;
        const periodeText = tds[1].textContent.trim();
        const totalGajiText = tds[7].textContent; // kolom Total Gaji
        const statusText = tds[8].textContent.trim(); // kolom Status

        // Cocokkan bulan/tahun
        let match = true;
        if (filterBulan) {
            const bulanNama = periodeText.split(' ')[0];
            const bulanIndex = new Date(Date.parse(bulanNama + " 1, 2020")).getMonth() + 1;
            if (parseInt(filterBulan) !== bulanIndex) match = false;
        }
        if (filterTahun) {
            const tahun = periodeText.split(' ')[1];
            if (tahun !== filterTahun) match = false;
        }
        if (!match) return;

        const nilai = parseInt((totalGajiText || '').replace(/[^\d]/g, '')) || 0;
        totalGajiSemua += nilai;
        countSemua += 1;
        if (statusText.toLowerCase().includes('dibayar')) {
            totalDibayar += nilai;
            countDibayar += 1;
        } else {
            totalBelumDibayar += nilai;
        }
    });

    // Rata-rata berdasarkan baris yang cocok
    const rata = countSemua > 0 ? Math.round(totalGajiSemua / countSemua) : 0;

    // Update kartu
    const elDibayar = document.getElementById('statTotalDibayar');
    const elBelum = document.getElementById('statBelumDibayar');
    const elKaryawan = document.getElementById('statKaryawanDibayar');
    const elRata = document.getElementById('statRataRata');
    if (elDibayar) elDibayar.textContent = formatRupiahInt(totalDibayar);
    if (elBelum) elBelum.textContent = formatRupiahInt(totalBelumDibayar);
    if (elKaryawan) elKaryawan.textContent = countDibayar.toString();
    if (elRata) elRata.textContent = formatRupiahInt(rata);
}

// Data gaji yang sudah ada (untuk cek duplikasi karyawan+bulan+tahun)
const existingSalariesData = @json(($salaries ?? collect())->map->only(['employee_id','bulan','tahun'])->values());

// Helper: format ribuan
function formatRupiahInt(n) {
    return (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Set default filter ke bulan/tahun saat ini ketika halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
    const fb = document.getElementById('filterBulan');
    const ft = document.getElementById('filterTahun');
    const now = new Date();
    if (fb && !fb.value) fb.value = String(now.getMonth() + 1);
    if (ft && !ft.value) ft.value = String(now.getFullYear());
    // Terapkan filter awal dan hitung kartu
    filterKaryawanTable();
});

// Tampilkan toast duplikasi
function showDuplicateToast() {
    const toast = document.getElementById('toast-duplicate');
    if (!toast) return;
    toast.classList.remove('show');
    void toast.offsetWidth; // reflow untuk restart animasi
    toast.classList.add('show');
    // auto hide via CSS animation
    setTimeout(() => toast.classList.remove('show'), 3300);
}

// Cek duplikasi berdasarkan pilihan form saat ini (non-blokir)
function checkDuplicateSelection() {
    const empEl = document.getElementById('employee_select');
    const bulanEl = document.querySelector('#tambahModal [name="bulan"]');
    const tahunEl = document.querySelector('#tambahModal [name="tahun"]');
    if (!empEl || !bulanEl || !tahunEl) return;
    const employeeId = parseInt(empEl.value);
    const bulan = parseInt(bulanEl.value);
    const tahun = parseInt(tahunEl.value);
    if (isNaN(employeeId) || isNaN(bulan) || isNaN(tahun)) return;
    const exists = (existingSalariesData || []).some(s => parseInt(s.employee_id) === employeeId && parseInt(s.bulan) === bulan && parseInt(s.tahun) === tahun);
    if (exists) showDuplicateToast();
}

// Data salary untuk validasi payroll (ambil dari backend, array of {bulan, tahun})
const existingPayrolls = @json(($salaries ?? [])->map(fn($s) => ['bulan' => $s->bulan, 'tahun' => $s->tahun])->unique());

// Validasi generate payroll agar hanya untuk bulan/tahun yang belum ada
document.addEventListener('DOMContentLoaded', function() {
    const payrollForm = document.querySelector('#payrollModal form');
    if (payrollForm) {
        payrollForm.addEventListener('submit', function(e) {
            const bulan = parseInt(payrollForm.querySelector('[name="bulan"]').value);
            const tahun = parseInt(payrollForm.querySelector('[name="tahun"]').value);
            const exists = existingPayrolls.some(p => parseInt(p.bulan) === bulan && parseInt(p.tahun) === tahun);
            if (exists) {
                alert('Payroll untuk bulan dan tahun tersebut sudah ada!');
                e.preventDefault();
            }
        });
    }

    // Peringatan hanya saat submit (tidak pada perubahan input)
});
</script>

<!-- Modal Pilih Tahun -->
<div id="yearModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-700">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pilih Tahun</h3>
                <button type="button" onclick="closeYearModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-4 gap-2 max-h-60 overflow-y-auto">
                @foreach(($allYears ?? []) as $year)
                    <button type="button" onclick="selectYear({{ $year }})" 
                            class="year-btn px-3 py-2 text-sm font-medium rounded-md border transition-colors {{ $year == now()->format('Y') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600' }}" 
                            data-year="{{ $year }}">
                        {{ $year }}
                    </button>
                @endforeach
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeYearModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal functions
function openYearModal() {
    document.getElementById('yearModal').classList.remove('hidden');
}

// Set filter tahun ke tahun saat ini saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    const filterTahun = document.getElementById('filterTahun');
    if (filterTahun && filterTahun.value) {
        filterKaryawanTable();
    }
});

function closeYearModal() {
    document.getElementById('yearModal').classList.add('hidden');
}

function selectYear(year) {
    // Update filter tahun dengan tahun yang dipilih
    const filterTahun = document.getElementById('filterTahun');
    const yearButtonText = document.getElementById('yearButtonText');
    
    if (filterTahun) {
        filterTahun.value = year;
        filterKaryawanTable();
    }
    
    // Update text pada tombol untuk menampilkan tahun yang dipilih
    if (yearButtonText) {
        yearButtonText.textContent = 'Pilih Tahun (' + year + ')';
    }
    
    // Update highlighting pada modal
    document.querySelectorAll('.year-btn').forEach(btn => {
        if (btn.dataset.year == year) {
            btn.className = 'year-btn px-3 py-2 text-sm font-medium rounded-md border transition-colors bg-indigo-600 text-white border-indigo-600';
        } else {
            btn.className = 'year-btn px-3 py-2 text-sm font-medium rounded-md border transition-colors bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600';
        }
    });
    
    closeYearModal();
}

// Close modal when clicking outside
document.getElementById('yearModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeYearModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeYearModal();
    }
});
</script>

@endsection
