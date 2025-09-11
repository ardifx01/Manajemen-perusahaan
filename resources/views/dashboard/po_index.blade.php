@extends('layouts.app')
@section('title', 'PURCHASE ORDER VENDOR')

@push('styles')
<!-- Optional modern font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root { --brand-blue: #2563EB; }
  .fade-in { animation: fadeIn 220ms ease-out; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
  .bump { animation: bump 260ms ease-out; }
  @keyframes bump { 0% { transform: scale(1); } 50% { transform: scale(1.025); } 100% { transform: scale(1); } }
  .font-inter { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, 'Apple Color Emoji','Segoe UI Emoji'; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-transparent py-4 sm:py-8">
    <div class="max-w-6xl mx-auto px-2 sm:px-4">
        <!-- Header Section -->
        <div class="bg-white/95 dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
            <!-- Made header responsive with flex-col on mobile -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100">
                        @isset($po) 
                            <i class="fas fa-edit text-blue-600 mr-2"></i>Edit Purchase Order
                        @else 
                            <i class="fas fa-plus-circle text-green-600 mr-2"></i>Input Purchase Order
                        @endisset
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm sm:text-base">Kelola data purchase order dengan mudah</p>
                </div>
                <!-- Added real-time clock -->
                <div class="text-left sm:text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400" id="current-date">{{ date('d M Y') }}</div>
                    <div class="text-xs text-gray-400 dark:text-gray-500" id="current-time">{{ date('H:i') }} WIB</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-700 p-4 mb-4 sm:mb-6 rounded-r-lg">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-400 dark:text-green-300 mr-3 mt-0.5"></i>
                    <p class="text-green-800 dark:text-green-300 text-sm sm:text-base">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-700 p-4 mb-4 sm:mb-6 rounded-r-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-500 dark:text-red-300 mt-0.5"></i>
                    <div>
                        <p class="text-red-800 dark:text-red-300 font-semibold mb-1">Perbaiki input berikut:</p>
                        <ul class="list-disc list-inside text-red-700 dark:text-red-200 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Bar: Back + Lihat Data PO -->
        <div class="mb-4">
            <div class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gradient-to-r from-white to-blue-50/60 dark:from-slate-900/60 dark:to-slate-800/60 shadow-sm p-3 sm:p-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-circle-info text-blue-600"></i>
                        <span>Gunakan tombol berikut untuk navigasi cepat.</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('po.invoice.index') }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium border transition bg-white text-blue-700 border-blue-200 hover:bg-blue-50 dark:bg-slate-900 dark:text-blue-300 dark:border-blue-900 dark:hover:bg-slate-800">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Data Invoice
                        </a>
                        <a id="btn-to-sj" href="{{ route('suratjalan.index', ['month' => now()->format('n'), 'year' => now()->format('Y')]) }}"
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <i class="fa-solid fa-table-list text-white"></i>
                            Lihat Data PO (Surat Jalan)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form id="po-form" action="@isset($po) {{ route('po.update', $po->id) }} @else {{ route('po.store') }} @endisset" method="POST" class="font-sans font-inter">
            @csrf
            @isset($po) @method('PUT') @endisset
            <!-- Nomor Urut Data Invoice (bukan No Invoice). Diisi dari query po_number saat datang dari Data Invoice -->
            <input type="hidden" name="po_number" value="{{ request('po_number') ?? old('po_number', $po->po_number ?? '') }}">
            <!-- Sumber navigasi, agar setelah simpan bisa kembali ke Data Invoice -->
            <input type="hidden" name="from" value="{{ request('from') }}">

            <!-- Unified form layout without separate sections -->
            <div class="bg-white/95 dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-xl shadow-lg p-4 sm:p-6">
                <!-- Made grid responsive: 1 col on mobile, 2 on tablet, 3 on desktop -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Customer Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-building text-blue-500 mr-1"></i>Customer
                        </label>
                        <select name="customer_id" id="customer" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" required>
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" 
                                        data-address1="{{ $c->address_1 ?? '' }}" 
                                        data-address2="{{ $c->address_2 ?? '' }}"
                                        data-delivery-nomor="{{ (explode('/', $c->delivery_note_number ?? '')[0] ?? '') }}"
                                        data-delivery-pt="{{ (explode('/', $c->delivery_note_number ?? '')[1] ?? '') }}"
                                        data-delivery-tahun="{{ (explode('/', $c->delivery_note_number ?? '')[2] ?? '') }}"
                                        data-invoice-nomor="{{ (explode('/', $c->invoice_number ?? '')[0] ?? '') }}"
                                        data-invoice-pt="{{ (explode('/', $c->invoice_number ?? '')[1] ?? '') }}"
                                        data-invoice-tahun="{{ (explode('/', $c->invoice_number ?? '')[2] ?? '') }}"
                                        data-payment-terms="{{ $c->payment_terms_days ?? 30 }}"
                                        data-debug-terms="{{ $c->payment_terms_days }}"
                                        @selected(old('customer_id', $po->customer_id ?? '') == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="customer-payment-info" class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg hidden">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
                                <div class="text-sm">
                                    <span class="font-medium text-blue-800 dark:text-blue-300">Payment Terms:</span>
                                    <span id="payment-terms-text" class="text-blue-700 dark:text-blue-200"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No PO -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-file-invoice text-green-500 mr-1"></i>No PO
                        </label>
                        <input type="text" name="no_po" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('no_po', $po->no_po ?? '') }}" required>
                    </div>

                    <!-- Tanggal PO -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-calendar text-red-500 mr-1"></i>Tanggal PO
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_po" class="date-input w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg pl-3 pr-12 sm:pl-4 sm:pr-12 py-2 sm:py-3 text-sm sm:text-base bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ request('tanggal_po') ?? old('tanggal_po', isset($po) && $po->tanggal_po ? \Carbon\Carbon::parse($po->tanggal_po)->format('Y-m-d') : '') }}" required>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-200">
                                <i class="fa-regular fa-calendar text-base"></i>
                            </span>
                        </div>
                    </div>

                    <!-- No Invoice (3 bagian) -->
                    <div class="space-y-2 md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-file-invoice-dollar text-indigo-500 mr-1"></i>No Invoice
                        </label>
                        @php
                            $noInvoiceParts = [];
                            if (isset($po) && $po->no_invoice) {
                                $noInvoiceParts = explode('/', $po->no_invoice);
                            }
                        @endphp
                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center w-full min-w-0">
                            <div class="flex gap-2 items-center w-full">
                                <input type="number" id="invoice_nomor" name="no_invoice_nomor" inputmode="numeric" pattern="[0-9]*" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[1] basis-1/4 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Nomor" value="{{ old('no_invoice_nomor', $noInvoiceParts[0] ?? '') }}">
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="text" id="invoice_pt" name="no_invoice_pt" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[2] basis-1/2 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="PT" value="{{ old('no_invoice_pt', $noInvoiceParts[1] ?? '') }}">
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="number" id="invoice_tanggal" name="no_invoice_tanggal" inputmode="numeric" pattern="[0-9]*" min="1" max="12" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[1] basis-1/4 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Bulan" value="{{ old('no_invoice_tanggal') }}">
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="number" id="invoice_tahun" name="no_invoice_tahun" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[1] basis-1/4 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Tahun" value="{{ old('no_invoice_tahun', $noInvoiceParts[2] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- No Surat Jalan -->
                    <!-- Made no surat jalan responsive with better mobile layout -->
                    <div class="space-y-2 md:col-span-2 lg:col-span-3 md:col-start-1 lg:col-start-1">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-truck text-purple-500 mr-1"></i>No Surat Jalan
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center w-full min-w-0">
                            @php
                                $noSuratJalanParts = [];
                                if (isset($po) && $po->no_surat_jalan) {
                                    $noSuratJalanParts = explode('/', $po->no_surat_jalan);
                                }
                            @endphp
                            <div class="flex gap-2 items-center w-full">
                                <input type="number" name="no_surat_jalan_nomor" id="delivery_nomor" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[1] basis-1/4 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Nomor" value="{{ old('no_surat_jalan_nomor', $noSuratJalanParts[0] ?? '') }}" required>
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="text" name="no_surat_jalan_pt" id="delivery_pt" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[2] basis-1/2 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="PT" value="{{ old('no_surat_jalan_pt', $noSuratJalanParts[1] ?? '') }}" required>
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="number" name="no_surat_jalan_tahun" id="delivery_tahun" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base flex-[1] basis-1/4 min-w-0 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Tahun" value="{{ old('no_surat_jalan_tahun', $noSuratJalanParts[2] ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Made alamat_1 required and editable, not readonly -->
                    <!-- Alamat 1 -->
                    <div class="space-y-2 md:col-start-1 lg:col-start-1 md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Alamat 1 <span class="text-red-500"></span>
                        </label>
                        <input type="text" name="address_1" id="address_1" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('address_1', $po->alamat_1 ?? '') }}" required placeholder="Masukkan alamat lengkap">
                    </div>

                    <!-- Made alamat_2 editable, not readonly -->
                    <!-- Alamat 2 -->
                    <div class="space-y-2 md:col-start-1 lg:col-start-1 md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Alamat 2
                        </label>
                        <input type="text" name="address_2" id="address_2" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('address_2', $po->alamat_2 ?? '') }}" placeholder="Alamat tambahan (opsional)">
                    </div>

                    <!-- CHANGED: Pengirim field from input text to select dropdown -->
                    <!-- Pengirim -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-user text-orange-500 mr-1"></i>Pengirim
                        </label>
                        <select name="pengirim" id="pengirim" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-500/30 transition-all duration-200">
                            <option value="">-- Pilih Pengirim --</option>
                            @foreach($pengirims as $p)
                                <option value="{{ $p->nama }}" @selected(old('pengirim', $po->pengirim ?? '') == $p->nama)>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kendaraan -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-car text-green-500 mr-1"></i>Kendaraan
                        </label>
                        <select name="kendaraan" id="kendaraan" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-500/30 transition-all duration-200">
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach($kendaraans as $k)
                                <option value="{{ $k->nama }}" data-nopol="{{ $k->no_polisi }}" @selected(old('kendaraan', $po->kendaraan ?? '') == $k->nama)>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- No Polisi -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <i class="fas fa-id-card text-yellow-500 mr-1"></i>No Polisi
                        </label>
                        <input type="text" name="no_polisi" id="no_polisi" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" value="{{ old('no_polisi', $po->no_polisi ?? '') }}" readonly>
                    </div>

                    <!-- Produk (Dynamic Items) -->
                    <div class="md:col-span-2 lg:col-span-3 space-y-4" id="items-container">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-boxes text-indigo-500 mr-2"></i>Produk Items
                        </h3>

                        <!-- Table-like header for items (desktop) -->
                        <div class="hidden md:grid md:grid-cols-12 gap-4 text-xs font-semibold text-gray-500 dark:text-gray-400 px-1">
                            <div class="md:col-span-4">Produk</div>
                            <div class="md:col-span-2">Quantity</div>
                            <div class="md:col-span-2">Harga</div>
                            <div class="md:col-span-3">Total</div>
                            <div class="md:col-span-1 text-right">Aksi</div>
                        </div>

                        <!-- Item Row Template (first row) -->
                        <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-4 border rounded-lg bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10">
                            <!-- Produk -->
                            <div class="space-y-2 md:col-span-4">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Produk</label>
                                <select name="items[0][produk_id]" class="produk-select w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-500/30" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produks as $p)
                                        <option value="{{ $p->id }}" data-harga-pcs="{{ $p->harga_pcs ?? 0 }}" data-harga-set="{{ $p->harga_set ?? 0 }}">{{ $p->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Quantity -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Quantity</label>
                                <div class="flex w-full min-w-0">
                                    <input type="number" name="items[0][qty]" class="item-qty border-2 border-gray-200 dark:border-gray-700 rounded-l-lg px-3 py-2 text-sm flex-auto min-w-0 bg-white dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-500/30" min="1" required>
                                    <select name="items[0][qty_jenis]" class="item-qty-jenis border-2 border-l-0 border-gray-200 dark:border-gray-700 rounded-r-lg px-2 pr-8 py-2 text-[10px] w-[68px] shrink-0 bg-white dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-500/30" required>
                                        <option value="PCS">PCS</option>
                                        <option value="SET">SET</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Harga -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Harga</label>
                                <input type="number" name="items[0][harga]" class="item-harga w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-gray-100 dark:bg-gray-800/80 text-gray-800 dark:text-gray-100" readonly>
                            </div>
                            <!-- Total -->
                            <div class="space-y-2 md:col-span-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Total</label>
                                <input type="number" name="items[0][total]" class="item-total w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-gray-100 dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 font-bold" readonly>
                            </div>
                            <!-- Remove Button -->
                            <div class="md:col-span-1 flex items-end justify-end">
                                <button type="button" title="Hapus item" class="remove-item-btn inline-flex items-center justify-center bg-red-500/90 hover:bg-red-500 text-white w-9 h-9 rounded-full shadow-sm transition -mr-2 sm:-mr-1">
                                    <i class="fa-solid fa-trash text-white text-lg leading-none"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Toolbar -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                            <button type="button" id="add-item-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm w-full sm:w-auto">
                                <i class="fas fa-plus-circle mr-1"></i>Tambah Produk
                            </button>
                            <!-- Grand Total Summary Card -->
                            <div id="grand-summary" class="mt-2 sm:mt-0 w-full sm:w-auto">
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm px-4 py-3 flex items-center justify-between gap-4">
                                    <span class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">Grand Total</span>
                                    <input type="number" name="grand_total" id="grand_total" class="w-40 sm:w-56 border-2 border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm sm:text-base bg-gray-100 dark:bg-gray-900/40 text-gray-800 dark:text-gray-100 font-bold text-right transition-all duration-200" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <!-- Made buttons responsive with flex-col on mobile -->
                    <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 items-stretch">
                            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base rounded-lg transition-all duration-200 flex items-center justify-center shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <i class="fas fa-save mr-2"></i>
                                @isset($po) Update PO @else Simpan PO @endisset
                            </button>

                            @isset($po)
                                <!-- Gunakan komponen aksi seragam untuk Hapus -->
                                <div class="w-full sm:w-auto">
                                    <x-table.action-buttons 
                                        :onEdit="null"
                                        deleteAction="{{ route('po.destroy', $po->id) }}"
                                        confirmText="Yakin ingin menghapus PO ini?" />
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function updateClock() {
        const now = new Date();
        const dateOptions = { day: '2-digit', month: 'short', year: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: false };
        
        const currentDate = now.toLocaleDateString('id-ID', dateOptions);
        const currentTime = now.toLocaleTimeString('id-ID', timeOptions);
        
        document.getElementById('current-date').textContent = currentDate;
        document.getElementById('current-time').textContent = currentTime + ' WIB';
    }
    
    // Update clock immediately and then every second
    updateClock();
    setInterval(updateClock, 1000);

    const kendaraanSelect = document.getElementById('kendaraan');
    const noPolisiInput = document.getElementById('no_polisi');

    const customerSelect = document.getElementById('customer');
    const address1Input = document.getElementById('address_1');
    const address2Input = document.getElementById('address_2');
    
    const deliveryNomorInput = document.getElementById('delivery_nomor');
    const deliveryPtInput = document.getElementById('delivery_pt');
    const deliveryTahunInput = document.getElementById('delivery_tahun');

    const pengirimSelect = document.getElementById('pengirim');

    const invoiceNomorInput = document.getElementById('invoice_nomor');
    const invoicePtInput = document.getElementById('invoice_pt');
    const invoiceTahunInput = document.getElementById('invoice_tahun');
    const invoiceBulanInput = document.getElementById('invoice_tanggal');
    const tanggalPOInput = document.querySelector('input[name="tanggal_po"]');

    // Batasi input hanya angka untuk field Nomor (invoice & surat jalan)
    function enforceDigitOnly(el) {
        if (!el) return;
        el.addEventListener('input', () => {
            el.value = (el.value || '').replace(/[^0-9]/g, '');
        });
        el.setAttribute('inputmode', 'numeric');
        el.setAttribute('pattern', '[0-9]*');
    }
    enforceDigitOnly(deliveryNomorInput);
    enforceDigitOnly(invoiceNomorInput);

    function addAutoFillEffect(element) {
        if (element && element.value) {
            element.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                element.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
        }
    }

    // Auto-fill Bulan/Tahun dari Tanggal PO
    function fillMonthYearFromTanggalPO() {
        if (!tanggalPOInput || !tanggalPOInput.value) return;
        const d = new Date(tanggalPOInput.value);
        if (isNaN(d.getTime())) return;
        const month = d.getMonth() + 1; // 1-12
        const year = d.getFullYear();

        if (invoiceBulanInput && (!invoiceBulanInput.value || invoiceBulanInput.value === '')) {
            invoiceBulanInput.value = month;
            addAutoFillEffect(invoiceBulanInput);
        }
        if (invoiceTahunInput && (!invoiceTahunInput.value || invoiceTahunInput.value === '')) {
            invoiceTahunInput.value = year;
            addAutoFillEffect(invoiceTahunInput);
        }
        if (deliveryTahunInput && (!deliveryTahunInput.value || deliveryTahunInput.value === '')) {
            deliveryTahunInput.value = year;
            addAutoFillEffect(deliveryTahunInput);
        }
    }

    // Dynamic multi-item logic
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item-btn');
    let itemIndex = itemsContainer ? itemsContainer.querySelectorAll('.item-row').length : 0;

    // Per-row calculation
    function calculateRowTotal(row) {
        const produkSelect = row.querySelector('.produk-select');
        const qtyInput = row.querySelector('.item-qty');
        const qtyJenisSelect = row.querySelector('.item-qty-jenis');
        const hargaInput = row.querySelector('.item-harga');
        const totalInput = row.querySelector('.item-total');

        if (!produkSelect || !qtyInput || !qtyJenisSelect || !hargaInput || !totalInput) return;

        const selected = produkSelect.options[produkSelect.selectedIndex];
        const qtyJenis = qtyJenisSelect.value;
        let harga = 0;
        if (selected && selected.value) {
            harga = qtyJenis === 'PCS' ? parseInt(selected.dataset.hargaPcs || 0) : parseInt(selected.dataset.hargaSet || 0);
        }
        const qty = parseInt(qtyInput.value || 0);
        const total = (harga || 0) * (qty || 0);
        hargaInput.value = harga || 0;
        totalInput.value = total || 0;

        if (total > 0) addAutoFillEffect(totalInput);
    }

    function updateGrandTotal() {
        const rows = document.querySelectorAll('.item-row');
        let grand = 0;
        rows.forEach(r => {
            const t = r.querySelector('.item-total');
            grand += parseInt((t && t.value) ? t.value : 0);
        });
        const grandInput = document.getElementById('grand_total');
        if (grandInput) {
            grandInput.value = grand;
            const summaryCard = document.getElementById('grand-summary');
            if (summaryCard) {
                summaryCard.classList.add('bump');
                setTimeout(() => summaryCard.classList.remove('bump'), 260);
            }
        }
    }

    function renumberRows() {
        const rows = itemsContainer.querySelectorAll('.item-row');
        rows.forEach((row, idx) => {
            const sel = row.querySelector('.produk-select');
            const qty = row.querySelector('.item-qty');
            const jenis = row.querySelector('.item-qty-jenis');
            const harga = row.querySelector('.item-harga');
            const total = row.querySelector('.item-total');
            if (sel) sel.name = `items[${idx}][produk_id]`;
            if (qty) qty.name = `items[${idx}][qty]`;
            if (jenis) jenis.name = `items[${idx}][qty_jenis]`;
            if (harga) harga.name = `items[${idx}][harga]`;
            if (total) total.name = `items[${idx}][total]`;
        });
        itemIndex = rows.length;
        // Show remove buttons only if more than one row
        const removeButtons = itemsContainer.querySelectorAll('.remove-item-btn');
        removeButtons.forEach(btn => btn.classList.toggle('hidden', rows.length <= 1));
    }

    function attachRowEvents(row) {
        const produkSelect = row.querySelector('.produk-select');
        const qtyInput = row.querySelector('.item-qty');
        const qtyJenisSelect = row.querySelector('.item-qty-jenis');
        const removeBtn = row.querySelector('.remove-item-btn');
        if (produkSelect) produkSelect.addEventListener('change', () => { calculateRowTotal(row); updateGrandTotal(); });
        if (qtyInput) qtyInput.addEventListener('input', () => { calculateRowTotal(row); updateGrandTotal(); });
        if (qtyJenisSelect) qtyJenisSelect.addEventListener('change', () => { calculateRowTotal(row); updateGrandTotal(); });
        if (removeBtn) removeBtn.addEventListener('click', () => {
            row.remove();
            renumberRows();
            updateGrandTotal();
        });
    }

    if (addItemBtn) {
        addItemBtn.addEventListener('click', () => {
            const template = itemsContainer.querySelector('.item-row');
            if (!template) return;
            const newRow = template.cloneNode(true);
            // Reset values
            const sel = newRow.querySelector('.produk-select');
            if (sel) sel.selectedIndex = 0;
            newRow.querySelectorAll('input').forEach(inp => inp.value = '');
            // Prevent duplicate listeners by replacing node
            const cleanRow = newRow.cloneNode(true);
            itemsContainer.insertBefore(cleanRow, itemsContainer.querySelector('.flex.flex-col') || null);
            cleanRow.classList.add('fade-in');
            attachRowEvents(cleanRow);
            renumberRows();
            calculateRowTotal(cleanRow);
            updateGrandTotal();
        });
    }

    function updateNoPolisi() {
        const selected = kendaraanSelect.options[kendaraanSelect.selectedIndex];
        const nopol = selected.dataset.nopol || '';
        noPolisiInput.value = nopol;
        addAutoFillEffect(noPolisiInput);
    }

    function updateCustomerAddresses() {
        const selected = customerSelect.options[customerSelect.selectedIndex];
        const paymentInfo = document.getElementById('customer-payment-info');
        const paymentTermsText = document.getElementById('payment-terms-text');
        
        if (selected.value) {
            const address1 = selected.getAttribute('data-address1') || '';
            const address2 = selected.getAttribute('data-address2') || '';
            
            const deliveryNomor = selected.getAttribute('data-delivery-nomor') || '';
            const deliveryPt = selected.getAttribute('data-delivery-pt') || '';
            const deliveryTahun = selected.getAttribute('data-delivery-tahun') || '';

            const invoiceNomor = selected.getAttribute('data-invoice-nomor') || '';
            const invoicePt = selected.getAttribute('data-invoice-pt') || '';
            const invoiceTahun = selected.getAttribute('data-invoice-tahun') || '';
            
            const paymentTerms = selected.getAttribute('data-payment-terms') || '30';
            const debugTerms = selected.getAttribute('data-debug-terms');
            
            // Debug log
            console.log('Customer selected:', selected.textContent);
            console.log('Payment terms from data-payment-terms:', paymentTerms);
            console.log('Payment terms from data-debug-terms:', debugTerms);
            console.log('Raw payment_terms_days value:', debugTerms);
            
            // Show payment terms notification
            paymentTermsText.textContent = `${paymentTerms} hari setelah tanggal invoice (Debug: ${debugTerms})`;
            paymentInfo.classList.remove('hidden');
            paymentInfo.classList.add('fade-in');
            
            // Auto-fill address
            address1Input.value = address1;
            address2Input.value = address2;
            addAutoFillEffect(address1Input);
            addAutoFillEffect(address2Input);

            // Auto-fill delivery & invoice parts (jangan override jika user sudah isi)
            if (deliveryNomorInput && !deliveryNomorInput.value) deliveryNomorInput.value = deliveryNomor;
            if (deliveryPtInput && !deliveryPtInput.value) deliveryPtInput.value = deliveryPt;
            if (deliveryTahunInput && !deliveryTahunInput.value) deliveryTahunInput.value = deliveryTahun;
            if (invoiceNomorInput && !invoiceNomorInput.value) invoiceNomorInput.value = invoiceNomor;
            if (invoicePtInput && !invoicePtInput.value) invoicePtInput.value = invoicePt;
            if (invoiceTahunInput && !invoiceTahunInput.value) invoiceTahunInput.value = invoiceTahun;

            if (address1Input) { address1Input.value = address1; if (address1) addAutoFillEffect(address1Input); address1Input.readOnly = true; }
            if (address2Input) { address2Input.value = address2; if (address2) addAutoFillEffect(address2Input); address2Input.readOnly = true; }
            // Biarkan NOMOR diisi manual oleh pengguna -> kosongkan dan jangan readonly
            if (deliveryNomorInput) { deliveryNomorInput.value = ''; deliveryNomorInput.placeholder = 'Nomor'; deliveryNomorInput.readOnly = false; }
            if (deliveryPtInput) { deliveryPtInput.readOnly = false; }
            // Tahun (Surat Jalan) bisa diisi manual: hanya prefill jika kosong, dan tetap editable
            if (deliveryTahunInput) {
                if (!deliveryTahunInput.value && deliveryTahun) {
                    deliveryTahunInput.value = deliveryTahun;
                    addAutoFillEffect(deliveryTahunInput);
                }
                deliveryTahunInput.readOnly = false;
            }

            // Biarkan NOMOR INVOICE diisi manual -> kosongkan dan jangan readonly
            if (invoiceNomorInput) { invoiceNomorInput.value = ''; invoiceNomorInput.placeholder = 'Nomor'; invoiceNomorInput.readOnly = false; }
            if (invoicePtInput) { invoicePtInput.value = invoicePt; if (invoicePt) addAutoFillEffect(invoicePtInput); invoicePtInput.readOnly = true; }
            // Tahun (Invoice) bisa diisi manual: hanya prefill jika kosong, dan tetap editable
            if (invoiceTahunInput) {
                if (!invoiceTahunInput.value && invoiceTahun) {
                    invoiceTahunInput.value = invoiceTahun;
                    addAutoFillEffect(invoiceTahunInput);
                }
                invoiceTahunInput.readOnly = false;
            }

            // Jika customer tidak punya alamat, tetap bisa edit
            if (!address1 && !address1Input.value) {
                address1Input.classList.add('border-yellow-400', 'bg-yellow-50');
                address1Input.placeholder = 'Customer tidak memiliki alamat, silakan isi manual';
                address1Input.readOnly = false;
            } else {
                address1Input.classList.remove('border-yellow-400', 'bg-yellow-50');
            }
        } else {
            // Hide payment terms notification when no customer selected
            paymentInfo.classList.add('hidden');
            paymentInfo.classList.remove('fade-in');
        }
    }

    // Tambahkan event agar saat user ganti customer, field jadi editable dulu lalu diisi dan dikunci lagi
    customerSelect.addEventListener('change', function() {
        updateCustomerAddresses();
    });

    // Attach to initial row
    document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
    renumberRows();
    kendaraanSelect.addEventListener('change', updateNoPolisi);
    customerSelect.addEventListener('change', updateCustomerAddresses);

    // Initialize on page load
    document.querySelectorAll('.item-row').forEach(row => calculateRowTotal(row));
    updateGrandTotal();
    updateNoPolisi();
    updateCustomerAddresses();

    // Hook: saat tanggal PO berubah, isi Bulan/Tahun otomatis
    if (tanggalPOInput) {
        tanggalPOInput.addEventListener('change', fillMonthYearFromTanggalPO);
        // Prefill sekali di awal jika ada nilai
        fillMonthYearFromTanggalPO();
    }
});
</script>

<script>
// Validasi front-end sederhana sebelum submit untuk mencegah kegagalan yang tidak terlihat
document.addEventListener('DOMContentLoaded', () => {
    // Update link Data PO (Surat Jalan) mengikuti tanggal_po
    const tanggalInput = document.querySelector('input[name="tanggal_po"]');
    const btnSJ = document.getElementById('btn-to-sj');
    function updateSuratJalanLink() {
        if (!tanggalInput || !btnSJ || !tanggalInput.value) return;
        const d = new Date(tanggalInput.value);
        if (isNaN(d.getTime())) return;
        const month = (d.getMonth() + 1);
        const year = d.getFullYear();
        const base = "{{ route('suratjalan.index') }}";
        btnSJ.href = base + `?month=${month}&year=${year}`;
    }
    updateSuratJalanLink();
    tanggalInput?.addEventListener('change', updateSuratJalanLink);

    const form = document.getElementById('po-form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const customer = document.getElementById('customer');
        const noPo = form.querySelector('input[name="no_po"]');
        const tgl = form.querySelector('input[name="tanggal_po"]');
        const rows = document.querySelectorAll('.item-row');
        let itemValid = false;
        rows.forEach(r => {
            const produk = r.querySelector('.produk-select');
            const qty = r.querySelector('.item-qty');
            if (produk && produk.value && qty && parseInt(qty.value || '0') > 0) {
                itemValid = true;
            }
        });

        const errors = [];
        if (!customer || !customer.value) errors.push('Customer wajib dipilih');
        if (!noPo || !noPo.value || noPo.value.trim() === '-' ) errors.push('No PO wajib diisi dan tidak boleh "-"');
        if (!tgl || !tgl.value) errors.push('Tanggal PO wajib diisi');
        if (!itemValid) errors.push('Minimal 1 item produk dengan Qty > 0');

        if (errors.length > 0) {
            e.preventDefault();
            alert('Form belum lengkap:\n- ' + errors.join('\n- '));
        }
    });
});
</script>
@endsection
{{-- WIP: tampilkan kolom no_invoice di tabel PO --}}
