@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header with gradient background - Responsive -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 dark:from-slate-800 dark:to-slate-700 rounded-lg shadow-lg p-4 md:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <!-- Customer Icon -->
                <div class="bg-white bg-opacity-20 dark:bg-white/10 p-2 md:p-3 rounded-full">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl md:text-3xl font-bold text-white">Data Customer</h1>
                    <p class="text-green-100 mt-1 text-sm md:text-base">Kelola data customer perusahaan</p>
                </div>
            </div>
            <!-- Made button responsive with smaller text on mobile -->
            <button onclick="openAddModal()" class="bg-white text-green-600 hover:bg-green-50 dark:bg-slate-700 dark:text-green-200 dark:hover:bg-slate-600 px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 text-sm md:text-base w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-green-300">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Customer</span>
            </button>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 dark:bg-emerald-900/30 dark:border-emerald-700 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 dark:text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
                <span class="text-green-700 dark:text-emerald-300 font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Data Table Card - Responsive -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Added horizontal scroll for mobile -->
        <div class="overflow-x-auto shadow-inner" id="tableContainer">
            <table class="min-w-full table-auto" id="customerTable">
                <thead class="bg-gradient-to-r from-green-500 to-green-600 dark:from-slate-700 dark:to-slate-700 text-white dark:text-slate-200">
                    <tr>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                <span>No</span>
                            </div>
                        </th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm min-w-[150px]">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Nama Customer</span>
                            </div>
                        </th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm min-w-[140px]">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>No Surat Jalan</span>
                            </div>
                        </th>
                        <!-- Made address columns responsive with min-width -->
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm min-w-[120px]">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Alamat 1</span>
                            </div>
                        </th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm min-w-[120px]">
                            <div class="flex items-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Alamat 2</span>
                            </div>
                        </th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-center font-semibold text-xs md:text-sm min-w-[140px]">
                            <div class="flex items-center justify-center space-x-1 md:space-x-2">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                                <span>Aksi</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($customers as $index => $customer)
                        <tr class="hover:bg-green-50 dark:hover:bg-slate-700 transition-colors duration-200">
                            <td class="px-3 md:px-6 py-3 md:py-4 text-gray-900 dark:text-slate-200 font-medium text-sm">{{ $index + 1 }}</td>
                            <td class="px-3 md:px-6 py-3 md:py-4">
                                <div class="flex items-center space-x-2 md:space-x-3">
                                    <div class="bg-green-100 dark:bg-slate-700 p-1 md:p-2 rounded-full">
                                        <svg class="w-3 h-3 md:w-4 md:h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-900 dark:text-slate-200 font-medium text-sm">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="px-3 md:px-6 py-3 md:py-4">
                                <span class="bg-blue-100 dark:bg-slate-700 text-blue-800 dark:text-blue-200 px-2 md:px-3 py-1 rounded-full text-xs font-medium">
                                    @if($customer->delivery_note_nomor || $customer->delivery_note_pt || $customer->delivery_note_tahun)
                                        {{ $customer->delivery_note_nomor ?? '-' }}/{{ $customer->delivery_note_pt ?? '-' }}/{{ $customer->delivery_note_tahun ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                            <td class="px-3 md:px-6 py-3 md:py-4">
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 px-2 md:px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $customer->address_1 ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 md:px-6 py-3 md:py-4">
                                <span class="bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 px-2 md:px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $customer->address_2 ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 md:px-6 py-3 md:py-4">
                                <!-- Made action buttons responsive - stack on mobile -->
                                <div class="flex flex-col sm:flex-row justify-center gap-1 sm:gap-2">
                                    <button onclick="openEditModal({{ $customer->id }}, '{{ $customer->name }}', '{{ $customer->address_1 }}', '{{ $customer->address_2 }}', '{{ $customer->delivery_note_nomor }}', '{{ $customer->delivery_note_pt }}', '{{ $customer->delivery_note_tahun }}')" 
                                            class="bg-yellow-500 dark:bg-yellow-500 hover:bg-yellow-600 dark:hover:bg-yellow-400 text-white px-2 md:px-4 py-1 md:py-2 rounded-lg text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400">
                                        <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-green-500 dark:bg-green-500 hover:bg-green-600 dark:hover:bg-green-400 text-white px-2 md:px-4 py-1 md:py-2 rounded-lg text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg w-full focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400"
                                                onclick="return confirm('Yakin ingin menghapus customer ini?')">
                                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="bg-gray-100 dark:bg-slate-700 p-6 rounded-full">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-slate-200 mb-2">Belum ada customer</h3>
                                        <p class="text-gray-500 dark:text-slate-400 mb-4">Mulai dengan menambahkan customer pertama Anda.</p>
                                        <button onclick="openAddModal()" class="bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-400 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                            Tambah Customer
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

<!-- Add Customer Modal - Responsive -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <!-- Made modal responsive with better mobile sizing -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-md sm:max-w-lg mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-slate-100 flex items-center space-x-2">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Customer</span>
            </h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="addCustomerForm" action="{{ route('customer.store') }}" method="POST" class="p-4">
            @csrf
            <div class="space-y-3">
                <div>
                    <label for="add_name" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Nama Customer</label>
                    <input type="text" id="add_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                           placeholder="Masukkan nama customer">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">No Surat Jalan</label>
                    <!-- Made delivery note inputs responsive -->
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 sm:gap-1 items-center">
                        <input type="text" id="add_delivery_note_nomor" name="delivery_note_nomor"
                               class="sm:col-span-2 px-2 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Nomor">
                        <span class="hidden sm:block text-center text-gray-500 dark:text-slate-400 font-medium text-sm">/</span>
                        <input type="text" id="add_delivery_note_pt" name="delivery_note_pt"
                               class="sm:col-span-2 px-2 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="PT">
                    </div>
                    <div class="mt-2">
                        <input type="text" id="add_delivery_note_tahun" name="delivery_note_tahun"
                               class="w-full px-2 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Tahun">
                    </div>
                </div>

                <div class="space-y-2">
                    <div>
                        <label for="add_address_1" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Alamat 1</label>
                        <input type="text" id="add_address_1" name="address_1"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Masukkan alamat 1">
                    </div>

                    <div>
                        <label for="add_address_2" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Alamat 2</label>
                        <input type="text" id="add_address_2" name="address_2"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Masukkan alamat 2">
                    </div>
                </div>
            </div>
            
            <!-- Made button section responsive -->
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-4 pt-3 border-t border-gray-200 dark:border-slate-700">
                <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg font-medium transition-colors duration-200 text-sm order-2 sm:order-1">
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn"
                        class="px-6 py-2 bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-400 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 text-sm order-1 sm:order-2 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400">
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

<!-- Edit Customer Modal - Responsive -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-md sm:max-w-lg mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-slate-100 flex items-center space-x-2">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Customer</span>
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editCustomerForm" method="POST" class="p-4">
            @csrf
            @method('PUT')
            <div class="space-y-3">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Nama Customer</label>
                    <input type="text" id="edit_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                           placeholder="Masukkan nama customer">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">No Surat Jalan</label>
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 sm:gap-1 items-center">
                        <input type="text" id="edit_delivery_note_nomor" name="delivery_note_nomor"
                               class="sm:col-span-2 px-2 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Nomor">
                        <span class="hidden sm:block text-center text-gray-500 dark:text-slate-400 font-medium text-sm">/</span>
                        <input type="text" id="edit_delivery_note_pt" name="delivery_note_pt"
                               class="sm:col-span-2 px-2 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="PT">
                    </div>
                    <div class="mt-2">
                        <input type="text" id="edit_delivery_note_tahun" name="delivery_note_tahun"
                               class="w-full px-2 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Tahun">
                    </div>
                </div>

                <div class="space-y-2">
                    <div>
                        <label for="edit_address_1" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Alamat 1</label>
                        <input type="text" id="edit_address_1" name="address_1"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Masukkan alamat 1">
                    </div>

                    <div>
                        <label for="edit_address_2" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Alamat 2</label>
                        <input type="text" id="edit_address_2" name="address_2"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-green-500 transition-colors duration-200 text-sm"
                               placeholder="Masukkan alamat 2">
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-4 pt-3 border-t border-gray-200 dark:border-slate-700">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg font-medium transition-colors duration-200 text-sm order-2 sm:order-1">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn"
                        class="px-6 py-2 bg-yellow-500 dark:bg-yellow-500 hover:bg-yellow-600 dark:hover:bg-yellow-400 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 text-sm order-1 sm:order-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400">
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
// Modal functions
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.getElementById('add_name').focus();
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
    document.getElementById('addCustomerForm').reset();
}

function openEditModal(id, name, address1, address2, deliveryNoteNomor, deliveryNotePt, deliveryNoteTahun) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editCustomerForm').action = `/customer/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_delivery_note_nomor').value = deliveryNoteNomor || '';
    document.getElementById('edit_delivery_note_pt').value = deliveryNotePt || '';
    document.getElementById('edit_delivery_note_tahun').value = deliveryNoteTahun || '';
    document.getElementById('edit_address_1').value = address1 || '';
    document.getElementById('edit_address_2').value = address2 || '';
    document.getElementById('edit_name').focus();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    document.getElementById('editCustomerForm').reset();
}

// Form submissions with loading states
document.getElementById('addCustomerForm').addEventListener('submit', function() {
    document.getElementById('addSubmitBtn').disabled = true;
    document.getElementById('addLoading').classList.remove('hidden');
});

document.getElementById('editCustomerForm').addEventListener('submit', function() {
    document.getElementById('editSubmitBtn').disabled = true;
    document.getElementById('editLoading').classList.remove('hidden');
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

// Responsive table scroll indicator
window.addEventListener('DOMContentLoaded', function() {
    const tableContainer = document.getElementById('tableContainer');
    const table = document.getElementById('customerTable');
    
    function checkScroll() {
        if (tableContainer.scrollWidth > tableContainer.clientWidth) {
            tableContainer.classList.add('shadow-inner');
        } else {
            tableContainer.classList.remove('shadow-inner');
        }
    }
    
    checkScroll();
    window.addEventListener('resize', checkScroll);
});
</script>
@endsection
