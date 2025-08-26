@extends('layouts.app')
@section('title', 'PURCHASE ORDER VENDOR')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 py-4 sm:py-8">
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

        <form action="@isset($po) {{ route('po.update', $po->id) }} @else {{ route('po.store') }} @endisset" method="POST">
            @csrf
            @isset($po) @method('PUT') @endisset

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
                                @php
                                    $deliveryParts = explode('/', $c->delivery_note_number ?? '');
                                    $deliveryNomor = $deliveryParts[0] ?? '';
                                    $deliveryPt = $deliveryParts[1] ?? '';
                                    $deliveryTahun = $deliveryParts[2] ?? '';
                                @endphp
                                <option value="{{ $c->id }}" 
                                        data-address1="{{ $c->address_1 ?? '' }}" 
                                        data-address2="{{ $c->address_2 ?? '' }}"
                                        data-delivery-nomor="{{ $deliveryNomor }}"
                                        data-delivery-pt="{{ $deliveryPt }}"
                                        data-delivery-tahun="{{ $deliveryTahun }}"
                                        @selected(old('customer_id', $po->customer_id ?? '') == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- No PO -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-file-invoice text-green-500 mr-1"></i>No PO
                        </label>
                        <input type="text" name="no_po" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('no_po', $po->no_po ?? '') }}" required>
                    </div>

                    <!-- Tanggal PO -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar text-red-500 mr-1"></i>Tanggal PO
                        </label>
                        <input type="date" name="tanggal_po" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('tanggal_po', isset($po) && $po->tanggal_po ? \Carbon\Carbon::parse($po->tanggal_po)->format('Y-m-d') : '') }}" required>
                    </div>

                    <!-- No Invoice (3 bagian) -->
                    <div class="space-y-2 md:col-span-1 lg:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-file-invoice-dollar text-indigo-500 mr-1"></i>No Invoice
                        </label>
                        @php
                            $noInvoiceParts = [];
                            if (isset($po) && $po->no_invoice) {
                                $noInvoiceParts = explode('/', $po->no_invoice);
                            }
                        @endphp
                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center max-w-full sm:max-w-lg">
                            <div class="flex gap-2 items-center w-full">
                                <input type="text" name="no_invoice_nomor" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base w-1/4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Nomor" value="{{ old('no_invoice_nomor', $noInvoiceParts[0] ?? '') }}">
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="text" name="no_invoice_pt" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base w-2/4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="PT" value="{{ old('no_invoice_pt', $noInvoiceParts[1] ?? '') }}">
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="number" name="no_invoice_tahun" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base w-1/4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Tahun" value="{{ old('no_invoice_tahun', $noInvoiceParts[2] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- No Surat Jalan -->
                    <!-- Made no surat jalan responsive with better mobile layout -->
                    <div class="space-y-2 md:col-span-1 lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-truck text-purple-500 mr-1"></i>No Surat Jalan
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center max-w-full sm:max-w-lg">
                            @php
                                $noSuratJalanParts = [];
                                if (isset($po) && $po->no_surat_jalan) {
                                    $noSuratJalanParts = explode('/', $po->no_surat_jalan);
                                }
                            @endphp
                            <div class="flex gap-2 items-center w-full">
                                <input type="number" name="no_surat_jalan_nomor" id="delivery_nomor" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base w-1/4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Nomor" value="{{ old('no_surat_jalan_nomor', $noSuratJalanParts[0] ?? '') }}" required>
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="text" name="no_surat_jalan_pt" id="delivery_pt" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base w-2/4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="PT" value="{{ old('no_surat_jalan_pt', $noSuratJalanParts[1] ?? '') }}" required>
                                <span class="text-gray-400 font-bold text-sm sm:text-base">/</span>
                                <input type="number" name="no_surat_jalan_tahun" id="delivery_tahun" class="border-2 border-gray-200 dark:border-gray-700 rounded-lg px-2 sm:px-3 py-2 sm:py-3 text-sm sm:text-base w-1/4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" placeholder="Tahun" value="{{ old('no_surat_jalan_tahun', $noSuratJalanParts[2] ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Made alamat_1 required and editable, not readonly -->
                    <!-- Alamat 1 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Alamat 1 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address_1" id="address_1" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('address_1', $po->alamat_1 ?? '') }}" required placeholder="Masukkan alamat lengkap">
                    </div>

                    <!-- Made alamat_2 editable, not readonly -->
                    <!-- Alamat 2 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Alamat 2
                        </label>
                        <input type="text" name="address_2" id="address_2" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition-all duration-200" value="{{ old('address_2', $po->alamat_2 ?? '') }}" placeholder="Alamat tambahan (opsional)">
                    </div>

                    <!-- CHANGED: Pengirim field from input text to select dropdown -->
                    <!-- Pengirim -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
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
                        <label class="block text-sm font-semibold text-gray-700">
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
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-id-card text-yellow-500 mr-1"></i>No Polisi
                        </label>
                        <input type="text" name="no_polisi" id="no_polisi" class="w-full border-2 border-gray-200 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-100" value="{{ old('no_polisi', $po->no_polisi ?? '') }}" readonly>
                    </div>

                    <!-- Produk (Dynamic Items) -->
                    <div class="md:col-span-2 lg:col-span-3 space-y-4" id="items-container">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-boxes text-indigo-500 mr-2"></i>Produk Items
                        </h3>

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
                            <button type="button" id="add-item-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all text-sm w-full sm:w-auto">
                                <i class="fas fa-plus-circle mr-1"></i>Tambah Produk
                            </button>
                            <div class="mt-2 sm:mt-0 flex justify-end items-center gap-3">
                                <span class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">Grand Total:</span>
                                <input type="number" name="grand_total" id="grand_total" class="w-40 sm:w-60 border-2 border-gray-300 dark:border-gray-700 rounded-lg px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-bold text-right" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <!-- Made buttons responsive with flex-col on mobile -->
                    <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-green-700 text-white px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center justify-center shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                @isset($po) Update PO @else Simpan PO @endisset
                            </button>

                        @isset($po)
                        <button type="button" onclick="document.getElementById('deleteForm').submit()" class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-700 text-white px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-trash mr-2"></i>Hapus PO
                        </button>
                        @endisset
                    </div>
                </div>
            </div>
        </form>

        @isset($po)
        <form id="deleteForm" action="{{ route('po.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endisset
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

    function addAutoFillEffect(element) {
        if (element && element.value) {
            element.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                element.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
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
        if (grandInput) grandInput.value = grand;
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
        if (selected.value) {
            const address1 = selected.getAttribute('data-address1') || '';
            const address2 = selected.getAttribute('data-address2') || '';
            
            const deliveryNomor = selected.getAttribute('data-delivery-nomor') || '';
            const deliveryPt = selected.getAttribute('data-delivery-pt') || '';
            const deliveryTahun = selected.getAttribute('data-delivery-tahun') || '';
            
            // Isi dan kunci (readonly) jika autofill
            if (!address1Input.value) {
                address1Input.value = address1;
                if (address1) {
                    addAutoFillEffect(address1Input);
                    address1Input.readOnly = true;
                }
            }
            if (!address2Input.value) {
                address2Input.value = address2;
                if (address2) {
                    addAutoFillEffect(address2Input);
                    address2Input.readOnly = true;
                }
            }
            if (!deliveryNomorInput.value) {
                deliveryNomorInput.value = deliveryNomor;
                if (deliveryNomor) {
                    addAutoFillEffect(deliveryNomorInput);
                    deliveryNomorInput.readOnly = true;
                }
            }
            if (!deliveryPtInput.value) {
                deliveryPtInput.value = deliveryPt;
                if (deliveryPt) {
                    addAutoFillEffect(deliveryPtInput);
                    deliveryPtInput.readOnly = true;
                }
            }
            if (!deliveryTahunInput.value) {
                deliveryTahunInput.value = deliveryTahun;
                if (deliveryTahun) {
                    addAutoFillEffect(deliveryTahunInput);
                    deliveryTahunInput.readOnly = true;
                }
            }

            // Jika customer tidak punya alamat, tetap bisa edit
            if (!address1 && !address1Input.value) {
                address1Input.classList.add('border-yellow-400', 'bg-yellow-50');
                address1Input.placeholder = 'Customer tidak memiliki alamat, silakan isi manual';
                address1Input.readOnly = false;
            } else {
                address1Input.classList.remove('border-yellow-400', 'bg-yellow-50');
            }
        }
    }

    // Tambahkan event agar saat user ganti customer, field jadi editable dulu lalu diisi dan dikunci lagi
    customerSelect.addEventListener('change', function() {
        address1Input.readOnly = false;
        address2Input.readOnly = false;
        deliveryNomorInput.readOnly = false;
        deliveryPtInput.readOnly = false;
        deliveryTahunInput.readOnly = false;
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
});
</script>
@endsection