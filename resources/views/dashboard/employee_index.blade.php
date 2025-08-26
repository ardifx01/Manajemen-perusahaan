@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="space-y-8">
    <style>
        /* Dark mode styles for employee modals (non-intrusive to light mode) */
        html.dark .modal-employee { background-color: #0f172a !important; color: #e5e7eb !important; } /* slate-900 bg, gray-200 text */
        html.dark .modal-employee .sticky { background-color: #0f172a !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark .modal-employee label { color: #e5e7eb !important; }
        html.dark .modal-employee input,
        html.dark .modal-employee textarea,
        html.dark .modal-employee select { background-color: #1f2937 !important; /* gray-800 */ color: #e5e7eb !important; border-color: #374151 !important; }
        html.dark .modal-employee input::placeholder,
        html.dark .modal-employee textarea::placeholder { color: #9ca3af !important; }
        /* Form content colors */
        html.dark .modal-employee form,
        html.dark .modal-employee form *:not(svg):not(path) { color: #e5e7eb !important; }
        html.dark .modal-employee form input,
        html.dark .modal-employee form textarea,
        html.dark .modal-employee form select { background-color: #1f2937 !important; color: #e5e7eb !important; border-color: #374151 !important; }
        html.dark .modal-employee form input::placeholder,
        html.dark .modal-employee form textarea::placeholder { color: #9ca3af !important; }
        /* Header text in modal */
        html.dark .modal-employee .sticky h3,
        html.dark .modal-employee .sticky p { color: #e5e7eb !important; }
    </style>
    <!-- Header dengan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-white/5 dark:border-white/10 dark:from-slate-900 dark:to-slate-800">
            <div class="flex items-center">
                <div class="bg-blue-500 text-white p-3 rounded-xl mr-4 shadow-lg dark:bg-blue-500/30 dark:text-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1 dark:text-gray-300">Total Karyawan</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ $totalKaryawan ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-white/5 dark:border-white/10 dark:from-slate-900 dark:to-slate-800">
            <div class="flex items-center">
                <div class="bg-green-500 text-white p-3 rounded-xl mr-4 shadow-lg dark:bg-green-500/25 dark:text-green-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1 dark:text-gray-300">Karyawan Aktif</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-300">{{ $karyawanAktif ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-white/5 dark:border-white/10 dark:from-slate-900 dark:to-slate-800">
            <div class="flex items-center">
                <div class="bg-indigo-500 text-white p-3 rounded-xl mr-4 shadow-lg dark:bg-indigo-500/30 dark:text-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1 dark:text-gray-300">Total Gaji</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-300">Rp {{ number_format($totalGaji ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-white/5 dark:border-white/10 dark:from-slate-900 dark:to-slate-800">
            <div class="flex items-center">
                <div class="bg-amber-500 text-white p-3 rounded-xl mr-4 shadow-lg dark:bg-amber-500/30 dark:text-amber-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1 dark:text-gray-300">Rata-rata Gaji</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-300">Rp {{ number_format($rataRataGaji ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Header dan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Data Karyawan</h1>
            <p class="text-gray-600 mt-1 dark:text-gray-300">Kelola informasi karyawan perusahaan</p>
        </div>
        <button onclick="openModal('tambahModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl flex items-center space-x-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span class="font-medium">Tambah Karyawan</span>
        </button>
    </div>

    <!-- Tabel Karyawan -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:bg-slate-900/40 dark:border-white/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Posisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Departemen</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-transparent dark:divide-white/10">
                    @forelse($employees ?? [] as $employee)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-white/5">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center border-2 border-blue-200 dark:from-blue-500/30 dark:to-blue-400/30 dark:border-blue-300/20">
                                    <span class="text-white font-semibold text-sm dark:text-blue-100">
                                        {{ strtoupper(substr($employee->nama_karyawan, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $employee->nama_karyawan }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $employee->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $employee->email }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->no_telepon ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->posisi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $employee->departemen }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium dark:text-gray-100">Rp {{ number_format($employee->gaji_pokok, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $employee->status === 'aktif' ? 'bg-green-100 text-green-800 border border-green-200 dark:bg-green-500/20 dark:text-green-200 dark:border-transparent' : 'bg-red-100 text-red-800 border border-red-200 dark:bg-red-500/20 dark:text-red-200 dark:border-transparent' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="editEmployee({{ json_encode($employee) }})" class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('employee.destroy', $employee) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus karyawan {{ $employee->nama_karyawan }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium dark:text-gray-300">Belum ada data karyawan</p>
                                <p class="text-gray-400 text-sm mt-1 dark:text-gray-400">Klik tombol "Tambah Karyawan" untuk menambah data pertama</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Karyawan -->
<div id="tambahModal" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="modal-employee relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-8 py-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Tambah Karyawan Baru</h3>
                    <p class="text-gray-600 mt-1">Lengkapi informasi karyawan di bawah ini</p>
                </div>
                <button onclick="closeModal('tambahModal')" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form method="POST" action="{{ route('employee.store') }}" class="p-8">
            @csrf
            
            <!-- Personal Information Section -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Personal
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Karyawan *</label>
                        <input type="text" name="nama_karyawan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
                        <input type="text" name="no_telepon" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat *</label>
                    <textarea name="alamat" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"></textarea>
                </div>
            </div>
            
            <!-- Job Information Section -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 112 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 112-2V6"/>
                    </svg>
                    Informasi Pekerjaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi *</label>
                        <input type="text" name="posisi" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen *</label>
                        <input type="text" name="departemen" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Salary Information Section -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z"/>
                    </svg>
                    Informasi Gaji
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Pokok *</label>
                        <input type="number" name="gaji_pokok" required min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeModal('tambahModal')" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                    Simpan Karyawan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Added Edit Modal -->
<!-- Modal Edit Karyawan -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="modal-employee relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-8 py-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Edit Karyawan</h3>
                    <p class="text-gray-600 mt-1">Perbarui informasi karyawan</p>
                </div>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="editForm" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <!-- Personal Information Section -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Personal
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Karyawan *</label>
                        <input type="text" id="edit_nama_karyawan" name="nama_karyawan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
                        <input type="text" id="edit_no_telepon" name="no_telepon" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat *</label>
                    <textarea id="edit_alamat" name="alamat" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"></textarea>
                </div>
            </div>
            
            <!-- Job Information Section -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 112 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 112-2V6"/>
                    </svg>
                    Informasi Pekerjaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi *</label>
                        <input type="text" id="edit_posisi" name="posisi" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen *</label>
                        <input type="text" id="edit_departemen" name="departemen" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                        <select id="edit_status" name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Salary Information Section -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z"/>
                    </svg>
                    Informasi Gaji
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Pokok *</label>
                        <input type="number" id="edit_gaji_pokok" name="gaji_pokok" required min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeModal('editModal')" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                    Update Karyawan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function editEmployee(employee) {
    // Populate form fields
    document.getElementById('edit_nama_karyawan').value = employee.nama_karyawan || '';
    document.getElementById('edit_email').value = employee.email || '';
    document.getElementById('edit_no_telepon').value = employee.no_telepon || '';
    document.getElementById('edit_alamat').value = employee.alamat || '';
    document.getElementById('edit_posisi').value = employee.posisi || '';
    document.getElementById('edit_departemen').value = employee.departemen || '';
    document.getElementById('edit_status').value = employee.status || '';
    document.getElementById('edit_gaji_pokok').value = employee.gaji_pokok || '';
    
    // Set form action URL
    document.getElementById('editForm').action = `{{ url('/employee') }}/${employee.id}`;
    
    // Open modal
    openModal('editModal');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const tambahModal = document.getElementById('tambahModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === tambahModal) {
        closeModal('tambahModal');
    }
    if (event.target === editModal) {
        closeModal('editModal');
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal('tambahModal');
        closeModal('editModal');
    }
});
</script>
@endsection
