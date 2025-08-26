@extends('layouts.app')

@section('content')
<div class="px-2 sm:px-4 py-3 sm:py-4">
    <!-- Header Section -->
    <div class="rounded-lg shadow-lg p-3 sm:p-4 mb-3 sm:mb-4 bg-gradient-to-r from-slate-50 to-slate-100 border border-gray-200 dark:border-transparent dark:bg-gradient-to-r dark:from-gray-700 dark:to-gray-800">
        <div class="flex flex-col space-y-3 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <h1 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">Data Surat Jalan</h1>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-200">Kelola data surat jalan dengan mudah</p>
                </div>
            </div>
            
            <!-- Export Controls -->
            <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                <div id="exportControls" class="hidden bg-gray-100 text-gray-700 dark:bg-white/20 dark:text-white backdrop-blur-sm rounded-lg px-2 py-1 text-center sm:text-left">
                    <span id="selectedCount" class="font-medium text-xs">0 dipilih</span>
                </div>
                <button id="exportBtn" onclick="exportSelected()"
                   class="w-full sm:w-auto h-9 px-4 rounded-lg font-medium text-white bg-green-600 hover:bg-green-700 transition-colors text-sm inline-flex items-center justify-center leading-none">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </button>
                <button type="button" onclick="generateInvoice()" class="w-full sm:w-auto h-9 px-4 rounded-lg font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors text-sm inline-flex items-center justify-center leading-none ml-0 sm:ml-0">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Invoice
                </button>
                <button type="button" onclick="downloadInvoicePDF()" class="w-full sm:w-auto h-9 px-4 rounded-lg font-medium text-white bg-red-600 hover:bg-red-700 transition-colors text-sm inline-flex items-center justify-center leading-none">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M16 6l-4-4-4 4M4 8v10a2 2 0 002 2h12a2 2 0 002-2V8" />
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    
        
        <!-- Made table much more compact with reduced padding and smaller text -->
        <div class="overflow-x-auto w-full">
            <table class="w-full table-auto min-w-max sm:min-w-full text-xs">
                <thead class="bg-gray-100 dark:bg-slate-700">
                    <tr>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight border-r border-gray-200 dark:border-slate-700 w-8">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-200 focus:ring-gray-500 dark:focus:ring-slate-500 w-3 h-3 bg-white dark:bg-slate-800">
                        </th>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight border-r border-gray-200 dark:border-slate-700">
                            <div class="flex items-center space-x-1">
                                <svg class="w-3 h-3 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="hidden sm:inline">Tanggal</span>
                                <span class="sm:hidden">Tgl</span>
                            </div>
                        </th>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight border-r border-gray-200 dark:border-slate-700">
                            <div class="flex items-center space-x-1">
                                <svg class="w-3 h-3 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="hidden sm:inline">No PO</span>
                                <span class="sm:hidden">PO</span>
                            </div>
                        </th>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight border-r border-gray-200 dark:border-slate-700">
                            <span>Customer</span>
                        </th>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight border-r border-gray-200 dark:border-slate-700">
                            <div class="flex items-center space-x-1">
                                <svg class="w-3 h-3 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="hidden sm:inline">No Surat Jalan</span>
                                <span class="sm:hidden">No SJ</span>
                            </div>
                        </th>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight border-r border-gray-200 dark:border-slate-700">
                            <div class="flex items-center space-x-1">
                                <svg class="w-3 h-3 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="hidden sm:inline">Invoice</span>
                                <span class="sm:hidden">Inv</span>
                            </div>
                        </th>
                        <th class="py-1.5 px-1.5 text-left text-xs font-medium text-gray-600 dark:text-slate-200 uppercase tracking-tight w-16">
                            <span>Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($suratjalan as $index => $pos)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                        <!-- Made all table cells much more compact with minimal padding -->
                        <td class="py-1 px-1.5 whitespace-nowrap text-xs border-r border-gray-200 dark:border-slate-700">
                            <input type="checkbox" name="selected_ids[]" value="{{ $pos->id }}" class="row-checkbox rounded border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-200 focus:ring-gray-500 dark:focus:ring-slate-500 w-3 h-3 bg-white dark:bg-slate-800">
                        </td>
                        <td class="py-1 px-1.5 whitespace-nowrap text-xs text-gray-900 dark:text-slate-200 border-r border-gray-200 dark:border-slate-700">
                            <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-600 dark:text-slate-200">
                                {{ \Carbon\Carbon::parse($pos->tanggal_po)->format('d/m/y') }}
                            </span>
                        </td>
                        <td class="py-1 px-1.5 whitespace-nowrap text-xs text-gray-900 dark:text-slate-200 border-r border-gray-200 dark:border-slate-700">
                            <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-600 dark:text-slate-200">
                                {{ $pos->no_po }}
                            </span>
                        </td>
                        <td class="py-1 px-1.5 whitespace-normal text-xs text-gray-900 dark:text-slate-200 border-r border-gray-200 dark:border-slate-700">
                            <span class="font-medium block">
                                {{ $pos->customer }}
                            </span>
                        </td>
                        <td class="py-1 px-1.5 whitespace-nowrap text-xs text-gray-900 dark:text-slate-200 border-r border-gray-200 dark:border-slate-700">
                            <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-600 dark:text-slate-200">
                                {{ $pos->no_surat_jalan }}
                            </span>
                        </td>
                        <td class="py-1 px-1.5 whitespace-nowrap text-xs text-gray-900 dark:text-slate-200 border-r border-gray-200 dark:border-slate-700">
                            <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-600 dark:text-slate-200">
                                {{ $pos->no_invoice ?? '-' }}
                            </span>
                        </td>
                        <td class="py-1 px-1.5 whitespace-nowrap text-xs font-medium">
                            <!-- Made action buttons more compact -->
                            <div class="flex items-center space-x-0.5">
                                <!-- Updated editSuratJalan function call to include pengirim parameter -->
                                <button onclick="editSuratJalan({{ $pos->id }}, '{{ $pos->tanggal_po }}', '{{ $pos->customer }}', '{{ $pos->alamat_1 ?? '' }}', '{{ $pos->alamat_2 ?? '' }}', '{{ $pos->no_surat_jalan }}', '{{ $pos->no_po }}', '{{ $pos->kendaraan }}', '{{ $pos->no_polisi }}', {{ $pos->qty }}, '{{ $pos->qty_jenis }}', '{{ $pos->produk_id }}', {{ $pos->total }}, '{{ $pos->pengirim ?? '' }}')" 
                                        class="text-yellow-600 hover:text-yellow-900 transition-colors p-0.5" title="Edit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteSuratJalan({{ $pos->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors p-0.5" title="Hapus">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm font-medium">Belum ada data surat jalan</p>
                                <p class="text-gray-400 text-xs">Data akan muncul setelah Anda menambahkan surat jalan pertama</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Export Form -->
<form id="exportForm" action="{{ route('suratjalan.export') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="selected_ids" id="selectedIds">
</form>

<!-- PDF Download Form (persistent to avoid double-click issue) -->
<form id="pdfForm" action="{{ route('suratjalan.invoice.pdf') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="selected_ids" id="selectedIdsPdf">
    <!-- keep persistent form to ensure stable submission in all browsers -->
    <button type="submit"></button>
    <!-- empty submit button for accessibility -->
    </form>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Edit Surat Jalan Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Surat Jalan</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal PO</label>
                        <input type="date" id="edit_tanggal_po" name="tanggal_po" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <input type="text" id="edit_customer" name="customer" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat 1</label>
                        <input type="text" id="edit_alamat_1" name="alamat_1" required
                               placeholder="Masukkan alamat lengkap"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat 2</label>
                        <input type="text" id="edit_alamat_2" name="alamat_2"
                               placeholder="Alamat tambahan (opsional)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No Surat Jalan</label>
                        <input type="text" id="edit_no_surat_jalan" name="no_surat_jalan" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No PO</label>
                        <input type="text" id="edit_no_po" name="no_po" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan</label>
                        <input type="text" id="edit_kendaraan" name="kendaraan" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No Polisi</label>
                        <input type="text" id="edit_no_polisi" name="no_polisi" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                        <input type="number" id="edit_qty" name="qty" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <input type="text" id="edit_qty_jenis" name="qty_jenis" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                        <select id="edit_produk_id" name="produk_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Pilih Produk</option>
                            @if(isset($produk))
                                @foreach($produk as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                        <input type="number" id="edit_total" name="total" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    
                    <!-- Added Pengirim input field to edit modal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pengirim</label>
                        <input type="text" id="edit_pengirim" name="pengirim"
                               placeholder="Nama pengirim"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<style>
    /* Force readable text colors inside invoice preview regardless of dark mode */
    #invoiceModal #invoiceContent, 
    #invoiceModal #invoiceContent * {
        color: #111827 !important; /* gray-900 */
    }
    #invoiceModal #invoiceContent h1, 
    #invoiceModal #invoiceContent h2, 
    #invoiceModal #invoiceContent strong {
        color: #111827 !important;
    }
</style>
<div id="invoiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-5 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header with Controls -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Preview Invoice</h3>
                <div class="flex space-x-2">
                    <button onclick="printInvoice()" class="w-full sm:w-auto h-9 px-4 rounded-lg font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors text-sm inline-flex items-center justify-center leading-none">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                    <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="invoiceContent" style="width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm; background: white; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; border: 1px solid #ddd;">
                <!-- Header -->
                <div style="display: flex; align-items: flex-start; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px;">
                    <img src="{{ asset('image/LOGO.png') }}" alt="PT. CAM JAYA ABADI Logo" style="width:80px; height:80px; object-fit:contain; margin-right:20px; background:transparent; border-radius:8px;">
                    <div style="flex: 1;">
                        <h2 style="margin: 0; font-size: 18px; font-weight: bold; color: #d32f2f;">PT. CAM JAYA ABADI</h2>
                        <p style="margin: 2px 0; font-size: 10px; line-height: 1.2; color: rgb(38, 73, 186)">
                            <strong>MANUFACTURING PROFESSIONAL WOODEN PALLET</strong><br>
                            <strong>KILN DRYING WOOD WORKING INDUSTRY</strong><br>
                            Factory & Office : Jl. Wahana Bakti No.28, Mangunjaya, Kec. Tambun Sel. Bekasi Jawa Barat 17510<br>
                            
                            Telp: (021) 6617 1626 - Fax: (021) 6617 3986
                        </p>
                    </div>
                </div>
                <!-- Customer Info -->
                <div style="margin-bottom: 20px;">
                    <div style="border: 1px solid #000; padding: 10px;">
                        <strong>Kepada Yth.</strong><br>
                        <span id="invoiceCustomer" style="font-weight: bold;"></span>
                        <br>
                        di .
                        <br>
                        <b><span id="invoiceAddress"></span></b>
                    </div>
                </div>
                <!-- Invoice Title -->
                <div style="text-align: center; margin: 30px 0;">
                    <h1 style="font-size: 48px; font-weight: bold; letter-spacing: 8px; margin: 0; color: #333;">INVOICE</h1>
                </div>
                <!-- Invoice Details -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <strong>No. PO : <span id="invoiceNoPO"></span></strong>
                    </div>
                    <div style="text-align: center;">
                        <strong>No : <span id="invoiceNo"></span></strong>
                    </div>
                    <div style="text-align: right;">
                        <strong>Date : <span id="invoiceDate"></span></strong>
                    </div>
                </div>
                <!-- Invoice Table -->
                <table style="width:100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; padding: 8px; text-align: center;">DESCRIPTION</th>
                            <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 15%;">QTY</th>
                            <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 20%;">UNIT PRICE</th>
                            <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 20%;">AMMOUNT</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceItems"></tbody>
                    <tfoot>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; text-align:right; font-weight: bold;">SUB TOTAL :</td>
                            <td id="invoiceSubtotalQty" style="border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;"></td>
                            <td style="border: 1px solid #000; padding: 8px;"></td>
                            <td id="invoiceSubtotal" style="border: 1px solid #000; padding: 8px; text-align:right;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; text-align:right; font-weight: bold;">PPN 11% :</td>
                            <td style="border: 1px solid #000; padding: 8px;"></td>
                            <td style="border: 1px solid #000; padding: 8px;"></td>
                            <td id="invoicePPN" style="border: 1px solid #000; padding: 8px; text-align:right;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; text-align:right; font-weight: bold;">GRAND TOTAL :</td>
                            <td style="border: 1px solid #000; padding: 8px;"></td>
                            <td style="border: 1px solid #000; padding: 8px;"></td>
                            <td id="invoiceGrandTotal" style="border: 1px solid #000; padding: 8px; text-align:right; font-weight: bold;"></td>
                        </tr>
                    </tfoot>
                </table>
                <!-- Payment Info and Signature -->
                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <div style="width: 60%;">
                        <p style="margin: 0; font-size: 11px; line-height: 1.3;">
                            <strong>Pembayaran Mohon Di Transfer Ke rekening</strong><br>
                            <strong>Bank BRI PEJATEN</strong><br>
                            <strong>NO REK : 1182-01-000039-30-3</strong><br>
                            <strong>ATAS NAMA : PT. CAM JAYA ABADI</strong>
                        </p>
                    </div>
                    <div style="width: 35%; text-align: center;">
                        <p style="margin: 0; margin-bottom: 25px;"><strong>Bekasi, <span id="invoiceDateLocation"></span></strong></p>
                        <div style="margin: 0 auto; width: 130px; margin-bottom: 20px;">
                            <img src="{{ asset('image/LOGO.png') }}" alt="Company Stamp" style="width: 130px; height: 90px; object-fit: contain; opacity: 0.9;">
                        </div>
                        <p style="margin: 0; font-size: 10px;">
                            <strong><u>NANIK PURNAMI</u></strong><br>
                            <span style="font-size: 8px;">DIREKTUR UTAMA</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const exportBtn = document.getElementById('exportBtn');
    const exportControls = document.getElementById('exportControls');
    const selectedCount = document.getElementById('selectedCount');
    const exportForm = document.getElementById('exportForm');
    const selectedIdsInput = document.getElementById('selectedIds');

    // Handle select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateExportControls();
    });

    // Handle individual row selection
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateExportControls();
        });
    });

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        selectAllCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < rowCheckboxes.length;
    }

    function updateExportControls() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            exportControls.classList.remove('hidden');
            selectedCount.textContent = `${count} dipilih`;
        } else {
            exportControls.classList.add('hidden');
        }
        // Always allow export (no selection => export all)
        exportBtn.disabled = false;
    }
});

function exportSelected() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
    console.log(selectedIds); // Pastikan ID yang dikirim benar
    
    // Jika tidak ada yang dipilih, biarkan kosong untuk ekspor semua

    const exportBtn = document.getElementById('exportBtn');
    const exportForm = document.getElementById('exportForm');
    const selectedIdsInput = document.getElementById('selectedIds');

    // Show loading state
    exportBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-yellow-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mengekspor...
    `;
    exportBtn.disabled = true;

    // Set selected IDs (or empty) and submit form
    selectedIdsInput.value = selectedIds.length > 0 ? JSON.stringify(selectedIds) : '';
    exportForm.submit();

    // Reset button after a delay
    setTimeout(() => {
        exportBtn.innerHTML = `
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export Excel
        `;
        exportBtn.disabled = false;
    }, 3000);
}

function deleteSuratJalan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data surat jalan ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/suratjalan/${id}`;
        form.submit();
    }
}

// Updated editSuratJalan function to handle pengirim parameter
function editSuratJalan(id, tanggal, customer, alamat1, alamat2, noSuratJalan, noPo, kendaraan, noPolisi, qty, jenis, produkId, total, pengirim) {
    // Set form action
    document.getElementById('editForm').action = `/suratjalan/${id}`;
    
    // Fill form fields
    document.getElementById('edit_tanggal_po').value = tanggal;
    document.getElementById('edit_customer').value = customer;
    document.getElementById('edit_alamat_1').value = alamat1 || '';
    document.getElementById('edit_alamat_2').value = alamat2 || '';
    document.getElementById('edit_no_surat_jalan').value = noSuratJalan;
    document.getElementById('edit_no_po').value = noPo;
    document.getElementById('edit_kendaraan').value = kendaraan;
    document.getElementById('edit_no_polisi').value = noPolisi;
    document.getElementById('edit_qty').value = qty;
    document.getElementById('edit_qty_jenis').value = jenis;
    document.getElementById('edit_pengirim').value = pengirim || '';
    setTimeout(() => {
        document.getElementById('edit_produk_id').value = produkId;
    }, 100);
    document.getElementById('edit_total').value = total;
    
    // Show modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Generate Invoice dengan format PT. CAM JAYA ABADI
function generateInvoice() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Pilih minimal satu data untuk membuat invoice');
        return;
    }
    const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

    fetch("{{ route('suratjalan.invoice.data') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(res => res.json())
    .then(res => {
        if (res.data && res.data.length > 0) {
            populateInvoice(res.data);
            document.getElementById('invoiceModal').classList.remove('hidden');
        } else {
            alert('Data tidak ditemukan!');
        }
    });
}

function populateInvoice(data) {
    // Data customer & alamat dari suratjalan pertama (PO header)
    const first = data[0];
    document.getElementById('invoiceCustomer').textContent = first.customer;
    let addressText = '';
    if (first.alamat_1) addressText += first.alamat_1;
    if (first.alamat_2) addressText += (addressText ? ', ' : '') + first.alamat_2;
    
    // Format alamat dengan koma dan titik yang proper
    if (addressText) {
        // Pastikan ada titik di akhir jika belum ada
        if (!addressText.endsWith('.')) {
            addressText += '.';
        }
        // Capitalize first letter setelah koma
        addressText = addressText.replace(/,\s*([a-z])/g, ', $1'.toUpperCase());
        // Capitalize first letter
        addressText = addressText.charAt(0).toUpperCase() + addressText.slice(1);
    }
    
    document.getElementById('invoiceAddress').textContent = addressText || '-';

    // Invoice detail - gunakan tanggal dari database
    const dbDate = new Date(first.tanggal_po);
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const invoiceDate = `${dbDate.getDate()} ${monthNames[dbDate.getMonth()]} ${dbDate.getFullYear()}`;
    
    // No PO dari database column no_po
    document.getElementById('invoiceNoPO').textContent = first.no_po || '-';
    // No Invoice dari database column no_invoice
    document.getElementById('invoiceNo').textContent = first.no_invoice || '-';
    document.getElementById('invoiceDate').textContent = invoiceDate;
    document.getElementById('invoiceDateLocation').textContent = invoiceDate;

    // Tabel item - flatten all PO items
    const itemsContainer = document.getElementById('invoiceItems');
    itemsContainer.innerHTML = '';
    let subtotal = 0, totalQty = 0;
    let unitTypes = new Set(); // Track different unit types

    // Flatten items from each selected PO
    const allItems = [];
    data.forEach(po => {
        if (po.items && Array.isArray(po.items)) {
            po.items.forEach(it => allItems.push(it));
        }
    });

    allItems.forEach(it => {
        const qty = parseInt(it.qty || 0);
        const totalAmount = parseInt(it.total || 0);
        const unitPrice = qty > 0 ? Math.round(totalAmount / qty) : 0;
        subtotal += isNaN(totalAmount) ? 0 : totalAmount;
        totalQty += isNaN(qty) ? 0 : qty;

        // Product name from relation 'produk'
        const produkName = it.produk && (it.produk.nama_produk || it.produk.nama || it.produk.name)
            ? (it.produk.nama_produk || it.produk.nama || it.produk.name)
            : '-';

        // Use exact database value for qty_jenis
        const jenis = (it.qty_jenis && String(it.qty_jenis).trim() !== '' && String(it.qty_jenis) !== '0') ? it.qty_jenis : 'PCS';
        unitTypes.add(jenis);

        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="border: 1px solid #000; padding: 8px; vertical-align: top; font-weight: bold;">${produkName}</td>
            <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: top; font-weight: bold;">${qty} ${jenis}</td>
            <td style="border: 1px solid #000; padding: 8px; text-align: right; vertical-align: top; font-weight: bold;">Rp. ${unitPrice.toLocaleString('id-ID')}</td>
            <td style="border: 1px solid #000; padding: 8px; text-align: right; vertical-align: top; font-weight: bold;">Rp. ${totalAmount.toLocaleString('id-ID')}</td>
        `;
        itemsContainer.appendChild(row);
    });
    
    // Tambah baris kosong seperti di foto (10 baris kosong)
    for (let i = 0; i < 10; i++) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `
            <td style="border: 1px solid #000; padding: 8px; height: 25px;">&nbsp;</td>
            <td style="border: 1px solid #000; padding: 8px; text-align: center;">&nbsp;</td>
            <td style="border: 1px solid #000; padding: 8px; text-align: right;">&nbsp;</td>
            <td style="border: 1px solid #000; padding: 8px; text-align: right;">&nbsp;</td>
        `;
        itemsContainer.appendChild(emptyRow);
    }
    
    // Totals - use mixed units or most common unit instead of hardcoded "SET"
    const ppn = Math.round(subtotal * 0.11);
    const grandTotal = subtotal + ppn;
    
    // Display total qty with appropriate unit
    let qtyDisplay;
    if (unitTypes.size === 1) {
        // All items have same unit
        qtyDisplay = `${totalQty} ${Array.from(unitTypes)[0]}`;
    } else {
        // Mixed units - show as "Items" or keep original logic but don't hardcode "SET"
        qtyDisplay = `${totalQty} Items`;
    }
    
    document.getElementById('invoiceSubtotalQty').textContent = qtyDisplay;
    document.getElementById('invoiceSubtotal').textContent = subtotal.toLocaleString('id-ID');
    document.getElementById('invoicePPN').textContent = ppn.toLocaleString('id-ID');
    document.getElementById('invoiceGrandTotal').textContent = grandTotal.toLocaleString('id-ID');
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
}

function printInvoice() {
    const printContent = document.getElementById('invoiceContent').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="width: 210mm; min-height: 297mm; margin: 0; padding: 20mm; background: white; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4;">
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

// Trigger server-side PDF generation for selected Surat Jalan
function downloadInvoicePDF() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Pilih minimal satu data untuk membuat invoice PDF');
        return;
    }
    const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
    const pdfForm = document.getElementById('pdfForm');
    const pdfIds = document.getElementById('selectedIdsPdf');
    pdfIds.value = JSON.stringify(selectedIds);
    pdfForm.submit();
}

// Close modal when clicking outside
document.getElementById('invoiceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeInvoiceModal();
    }
});
</script>

<style>
/* Tooltip style */
.table-tooltip {
    position: relative;
    cursor: pointer;
}
.table-tooltip .tooltip-text {
    visibility: hidden;
    opacity: 0;
    width: max-content;
    max-width: 300px;
    background: #222;
    color: #fff;
    text-align: left;
    border-radius: 4px;
    padding: 6px 10px;
    position: absolute;
    z-index: 10;
    left: 50%;
    top: 110%;
    transform: translateX(-50%);
    font-size: 12px;
    transition: opacity 0.2s;
    white-space: pre-line;
    word-break: break-all;
}
.table-tooltip:hover .tooltip-text,
.table-tooltip:focus .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>
@endsection