@extends('layouts.app')

@section('title', 'Data Kendaraan')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 dark:from-slate-800 dark:to-slate-700 rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
        <!-- Made header fully responsive with flex-col on mobile -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <!-- Vehicle Icon -->
                <div class="bg-white bg-opacity-20 dark:bg-white/10 p-2 sm:p-3 rounded-full">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Data Kendaraan</h1>
                    <p class="text-blue-100 mt-1 text-sm sm:text-base">Kelola data kendaraan perusahaan</p>
                </div>
            </div>
            <!-- Made button full width on mobile -->
            <button onclick="openAddModal()" class="w-full sm:w-auto bg-white text-blue-600 dark:bg-slate-700 dark:text-blue-200 hover:bg-blue-50 dark:hover:bg-slate-600 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-blue-300">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-sm sm:text-base">Tambah Kendaraan</span>
            </button>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 dark:bg-emerald-900/30 dark:border-emerald-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400 dark:text-emerald-400 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-700 dark:text-emerald-300 font-medium text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Added scroll indicator and improved mobile table -->
        <div class="overflow-x-auto">
            <!-- Mobile scroll indicator -->
            <div class="sm:hidden bg-gray-50 dark:bg-slate-800 px-4 py-2 text-xs text-gray-500 dark:text-slate-400 border-b dark:border-slate-700">
                <div class="flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    <span>Geser untuk melihat lebih banyak</span>
                </div>
            </div>
            
            <table class="min-w-full table-auto">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-slate-700 dark:to-slate-700 text-white dark:text-slate-200">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold">
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                <span class="text-xs sm:text-sm">No</span>
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold min-w-[150px]">
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                                </svg>
                                <span class="text-xs sm:text-sm">Nama Kendaraan</span>
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold min-w-[120px]">
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span class="text-xs sm:text-sm">No Polisi</span>
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-center font-semibold min-w-[140px]">
                            <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                                <span class="text-xs sm:text-sm">Aksi</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($kendaraans as $index => $kendaraan)
                        <tr class="hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-200">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-900 dark:text-slate-200 font-medium text-sm sm:text-base">{{ $index + 1 }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="bg-blue-100 dark:bg-slate-700 p-1.5 sm:p-2 rounded-full flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-900 dark:text-slate-200 font-medium text-sm sm:text-base truncate">{{ $kendaraan->nama }}</span>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                                    {{ $kendaraan->no_polisi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <!-- Made action buttons stack vertically on mobile -->
                                <div class="flex flex-col sm:flex-row justify-center space-y-1 sm:space-y-0 sm:space-x-2">
                                    <button onclick="openEditModal({{ $kendaraan->id }}, '{{ $kendaraan->nama }}', '{{ $kendaraan->no_polisi }}')" 
                                            class="bg-yellow-500 dark:bg-yellow-500 hover:bg-yellow-600 dark:hover:bg-yellow-400 text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST" class="inline w-full sm:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full sm:w-auto bg-red-500 dark:bg-red-500 hover:bg-red-600 dark:hover:bg-red-400 text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400"
                                                onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="flex flex-col items-center space-y-3 sm:space-y-4">
                                    <div class="bg-gray-100 dark:bg-slate-700 p-4 sm:p-6 rounded-full">
                                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-slate-200 mb-2">Belum ada kendaraan</h3>
                                        <p class="text-gray-500 dark:text-slate-400 mb-3 sm:mb-4 text-sm sm:text-base">Mulai dengan menambahkan kendaraan pertama Anda.</p>
                                        <button onclick="openAddModal()" class="bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                                            Tambah Kendaraan
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Kendaraan Modal -->
<!-- Made modal fully responsive with better mobile layout -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-sm sm:max-w-md mx-2 sm:mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-slate-100 flex items-center space-x-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Kendaraan</span>
            </h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200 p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="addKendaraanForm" action="{{ route('kendaraan.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label for="add_nama" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 sm:mb-2">Nama Kendaraan</label>
                    <input type="text" id="add_nama" name="nama" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama kendaraan">
                </div>
                
                <div>
                    <label for="add_no_polisi" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 sm:mb-2">No Polisi</label>
                    <input type="text" id="add_no_polisi" name="no_polisi"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nomor polisi">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-gray-200 dark:border-slate-700">
                <button type="button" onclick="closeAddModal()" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn"
                        class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <span>Simpan</span>
                    <div id="addLoading" class="hidden">
                        <svg class="animate-spin h-3 h-3 sm:h-4 sm:w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Kendaraan Modal -->
<!-- Made edit modal fully responsive with better mobile layout -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-sm sm:max-w-md mx-2 sm:mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-slate-100 flex items-center space-x-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Kendaraan</span>
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200 p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editKendaraanForm" method="POST" class="p-4 sm:p-6">
            @csrf
            @method('PUT')
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label for="edit_nama" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 sm:mb-2">Nama Kendaraan</label>
                    <input type="text" id="edit_nama" name="nama" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama kendaraan">
                </div>
                
                <div>
                    <label for="edit_no_polisi" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 sm:mb-2">No Polisi</label>
                    <input type="text" id="edit_no_polisi" name="no_polisi"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nomor polisi">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-gray-200 dark:border-slate-700">
                <button type="button" onclick="closeEditModal()" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn"
                        class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-yellow-500 dark:bg-yellow-500 hover:bg-yellow-600 dark:hover:bg-yellow-400 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400">
                    <span>Update</span>
                    <div id="editLoading" class="hidden">
                        <svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functions
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.getElementById('add_nama').focus();
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
    document.getElementById('addKendaraanForm').reset();
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

function openEditModal(id, nama, noPolisi) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editKendaraanForm').action = `/kendaraan/${id}`;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_no_polisi').value = noPolisi || '';
    document.getElementById('edit_nama').focus();
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.getElementById('editKendaraanForm').reset();
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Form submissions with loading states
document.getElementById('addKendaraanForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('addSubmitBtn');
    const loading = document.getElementById('addLoading');
    submitBtn.disabled = true;
    loading.classList.remove('hidden');
    submitBtn.querySelector('span').textContent = 'Menyimpan...';
});

document.getElementById('editKendaraanForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('editSubmitBtn');
    const loading = document.getElementById('editLoading');
    submitBtn.disabled = true;
    loading.classList.remove('hidden');
    submitBtn.querySelector('span').textContent = 'Mengupdate...';
});

// Close modal when clicking outside
document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
});

// Mobile-friendly form validation
document.getElementById('add_nama').addEventListener('input', function() {
    this.setCustomValidity('');
});

document.getElementById('edit_nama').addEventListener('input', function() {
    this.setCustomValidity('');
});

// Auto-resize modal content on mobile orientation change
window.addEventListener('orientationchange', function() {
    setTimeout(function() {
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                // Force reflow to adjust modal size
                modal.style.display = 'none';
                modal.offsetHeight; // Trigger reflow
                modal.style.display = 'flex';
            }
        });
    }, 100);
});
</script>
@endsection
