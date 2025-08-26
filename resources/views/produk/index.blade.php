@extends('layouts.app')
@section('title', 'Data Produk')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 dark:from-slate-800 dark:to-slate-700 rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
        <!-- Made header responsive with flex-col on mobile, flex-row on larger screens -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <!-- Product Icon -->
                <div class="bg-white bg-opacity-20 dark:bg-white/10 p-2 sm:p-3 rounded-full">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Data Produk</h1>
                    <p class="text-red-100 mt-1 text-sm sm:text-base">Kelola data produk perusahaan</p>
                </div>
            </div>
            <!-- Made button full width on mobile, auto width on larger screens -->
            <button onclick="openAddModal()" class="w-full sm:w-auto bg-white text-red-600 dark:bg-slate-700 dark:text-red-200 hover:bg-red-50 dark:hover:bg-slate-600 px-4 sm:px-9 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Produk</span>
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
        <!-- Added scroll indicator and improved mobile table handling -->
        <div class="relative">
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                <!-- Added scroll indicator for mobile -->
                <div class="sm:hidden bg-gray-50 dark:bg-slate-800 px-4 py-2 text-xs text-gray-500 dark:text-slate-400 border-b dark:border-slate-700">
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        <span>Geser untuk melihat lebih banyak</span>
                    </div>
                </div>
                <table class="min-w-full table-auto">
                    <thead class="bg-gradient-to-r from-red-500 to-red-600 dark:from-slate-700 dark:to-slate-700 text-white dark:text-slate-200">
                        <tr>
                            <!-- Made table headers responsive with smaller padding on mobile -->
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-xs sm:text-sm">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    <span>No</span>
                                </div>
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-xs sm:text-sm min-w-[150px]">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span>Nama Produk</span>
                                </div>
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-xs sm:text-sm min-w-[120px]">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <span>Harga PCS</span>
                                </div>
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-xs sm:text-sm min-w-[120px]">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Harga SET</span>
                                </div>
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center font-semibold text-xs sm:text-sm min-w-[140px]">
                                <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($produks as $index => $produk)
                            <tr class="hover:bg-red-50 dark:hover:bg-slate-700 transition-colors duration-200">
                                <!-- Made table cells responsive with smaller padding on mobile -->
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-900 dark:text-slate-200 font-medium text-sm sm:text-base">{{ $index + 1 }}</td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        <div class="bg-red-100 dark:bg-slate-700 p-1 sm:p-2 rounded-full flex-shrink-0">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-900 dark:text-slate-200 font-medium text-sm sm:text-base truncate">{{ $produk->nama_produk }}</span>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <!-- Made price badges responsive -->
                                    <span class="bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap">
                                        {{ $produk->harga_pcs ? 'Rp ' . number_format($produk->harga_pcs, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <span class="bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap">
                                        {{ $produk->harga_set ? 'Rp ' . number_format($produk->harga_set, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <!-- Made action buttons stack vertically on mobile -->
                                    <div class="flex flex-col sm:flex-row justify-center space-y-1 sm:space-y-0 sm:space-x-2">
                                        <button onclick="openEditModal({{ $produk->id }}, '{{ $produk->nama_produk }}', {{ $produk->harga_pcs ?? 0 }}, {{ $produk->harga_set ?? 0 }})" 
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="inline w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white px-2 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg"
                                                    onclick="return confirm('Yakin ingin menghapus produk ini?')">
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
                                <td colspan="5" class="px-3 sm:px-6 py-8 sm:py-12 text-center">
                                    <!-- Made empty state responsive -->
                                    <div class="flex flex-col items-center space-y-3 sm:space-y-4">
                                        <div class="bg-gray-100 dark:bg-slate-700 p-4 sm:p-6 rounded-full">
                                            <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-slate-200 mb-2">Belum ada produk</h3>
                                            <p class="text-gray-500 dark:text-slate-400 mb-3 sm:mb-4 text-sm sm:text-base">Mulai dengan menambahkan produk pertama Anda.</p>
                                            <button onclick="openAddModal()" class="bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-400 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                                                Tambah Produk
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
</div>

<!-- Add Produk Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <!-- Made modal responsive with proper sizing -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-sm sm:max-w-md mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-slate-100 flex items-center space-x-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Produk</span>
            </h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200 p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="addProdukForm" action="{{ route('produk.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="add_nama_produk" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Produk</label>
                    <input type="text" id="add_nama_produk" name="nama_produk" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama produk">
                </div>
                
                <div>
                    <label for="add_harga_pcs" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga PCS</label>
                    <input type="number" id="add_harga_pcs" name="harga_pcs" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan harga per PCS" min="0">
                </div>
                
                <div>
                    <label for="add_harga_set" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga SET</label>
                    <input type="number" id="add_harga_set" name="harga_set" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan harga per SET" min="0">
                </div>
            </div>
            
            <!-- Made modal buttons responsive -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-slate-700">
                <button type="button" onclick="closeAddModal()" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn"
                        class="w-full sm:w-auto px-6 py-2 bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-400 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400">
                    <span>Simpan</span>
                    <div id="addLoading" class="hidden">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Produk Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-sm sm:max-w-md mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-slate-100 flex items-center space-x-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Produk</span>
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200 p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editProdukForm" method="POST" class="p-4 sm:p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="edit_nama_produk" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Produk</label>
                    <input type="text" id="edit_nama_produk" name="nama_produk" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama produk">
                </div>
                
                <div>
                    <label for="edit_harga_pcs" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga PCS</label>
                    <input type="number" id="edit_harga_pcs" name="harga_pcs" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan harga per PCS" min="0">
                </div>
                
                <div>
                    <label for="edit_harga_set" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga SET</label>
                    <input type="number" id="edit_harga_set" name="harga_set" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 transition-colors duration-200 text-sm sm:text-base"
                           placeholder="Masukkan harga per SET" min="0">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-slate-700">
                <button type="button" onclick="closeEditModal()" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn"
                        class="w-full sm:w-auto px-6 py-2 bg-yellow-500 dark:bg-yellow-500 hover:bg-yellow-600 dark:hover:bg-yellow-400 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400">
                    <span>Update</span>
                    <div id="editLoading" class="hidden">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
// Modal functions with responsive enhancements
function openAddModal() {
    const modal = document.getElementById('addModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('add_nama_produk').focus();
    // Prevent body scroll on mobile
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    const modal = document.getElementById('addModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('addProdukForm').reset();
    // Restore body scroll
    document.body.style.overflow = '';
}

function openEditModal(id, nama, hargaPcs, hargaSet) {
    const modal = document.getElementById('editModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('editProdukForm').action = `/produk/${id}`;
    document.getElementById('edit_nama_produk').value = nama;
    document.getElementById('edit_harga_pcs').value = hargaPcs || '';
    document.getElementById('edit_harga_set').value = hargaSet || '';
    document.getElementById('edit_nama_produk').focus();
    // Prevent body scroll on mobile
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('editProdukForm').reset();
    // Restore body scroll
    document.body.style.overflow = '';
}

// Form submissions with loading states
document.getElementById('addProdukForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('addSubmitBtn');
    const loading = document.getElementById('addLoading');
    submitBtn.disabled = true;
    loading.classList.remove('hidden');
});

document.getElementById('editProdukForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('editSubmitBtn');
    const loading = document.getElementById('editLoading');
    submitBtn.disabled = true;
    loading.classList.remove('hidden');
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

// Handle orientation change on mobile
window.addEventListener('orientationchange', function() {
    setTimeout(function() {
        // Recalculate modal positioning if needed
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        if (!addModal.classList.contains('hidden') || !editModal.classList.contains('hidden')) {
            // Force reflow
            document.body.offsetHeight;
        }
    }, 100);
});
</script>
@endsection
