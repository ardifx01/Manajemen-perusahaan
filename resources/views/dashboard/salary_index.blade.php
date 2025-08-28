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
                    <p class="text-xl font-bold text-green-600 dark:text-green-300">Rp {{ number_format($totalGajiDibayar ?? 0, 0, ',', '.') }}</p>
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
                    <p class="text-xl font-bold text-red-600 dark:text-red-300">Rp {{ number_format($totalGajiBelumDibayar ?? 0, 0, ',', '.') }}</p>
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
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-300">{{ $jumlahKaryawanDibayar ?? 0 }}</p>
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
                    <p class="text-xl font-bold text-purple-600 dark:text-purple-300">Rp {{ number_format($rataRataGaji ?? 0, 0, ',', '.') }}</p>
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
            <!-- Filter Tahun -->
            <select id="filterTahun" class="appearance-none no-arrow px-3 py-2 border border-gray-300 rounded-md dark:bg-slate-800/60 dark:border-white/10 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500/40" onchange="filterKaryawanTable()">
                <option value="">Semua  Tahun</option>
                @for($i = 2020; $i <= 2030; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <button onclick="openModal('tambahModal')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Tambah Gaji</span>
            </button>
        </div>
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
        <form method="POST" action="{{ route('salary.store') }}" class="space-y-4" onsubmit="prepareFormSubmission(this)">
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
    
    const currencyFields = ['tunjangan', 'bonus', 'lembur', 'potongan_pajak', 'potongan_bpjs', 'potongan_lain'];
    
    currencyFields.forEach(field => {
        const displayField = document.getElementById(field + '_display');
        const hiddenField = document.getElementById(field + '_value');
        
        if (displayField && hiddenField) {
            // Only update if hidden field is empty but display field has value
            if (!hiddenField.value && displayField.value) {
                const numericValue = displayField.value.replace(/[^\d]/g, '') || '0';
                hiddenField.value = numericValue;
            }
            console.log('[v0] Field', field, 'value:', hiddenField.value);
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
    rows.forEach(row => {
        const namaCell = row.querySelector('td');
        if (!namaCell) return;
        const nama = namaCell.textContent.toLowerCase();

        // Ambil periode dari kolom kedua
        const periodeCell = row.querySelectorAll('td')[1];
        let show = nama.includes(input);

        if (periodeCell) {
            const periodeText = periodeCell.textContent.trim();
            // Cek bulan
            if (filterBulan) {
                const bulanNama = periodeText.split(' ')[0];
                const bulanIndex = new Date(Date.parse(bulanNama +" 1, 2020")).getMonth() + 1;
                if (parseInt(filterBulan) !== bulanIndex) show = false;
            }
            // Cek tahun
            if (filterTahun) {
                const tahun = periodeText.split(' ')[1];
                if (tahun !== filterTahun) show = false;
            }
        }
        row.style.display = show ? '' : 'none';
    });
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
});
</script>

@endsection
