@extends('layouts.app')

@section('content')
<div class="w-full px-2 sm:px-6 lg:px-8 py-4 sm:py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-3 sm:mb-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-slate-100">Tambah Barang Masuk</h1>
                <p class="text-gray-500 dark:text-slate-400 text-xs">Input stok masuk</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('barang.masuk.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
                Tabel Barang Masuk
            </a>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-lg w-full">
        <div class="px-6 pt-5 pb-2 border-t-4 border-blue-600 rounded-t-2xl">
            <h3 class="text-base font-semibold text-gray-900 dark:text-slate-100">Form Barang Masuk</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Isi data di bawah ini untuk menambah stok</p>
        </div>
        <div class="px-6 py-5">
            <form method="POST" action="{{ route('barang.masuk.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-5">
                    <!-- Produk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Barang</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </span>
                            <select id="produkSelectMasuk" name="produk_id" class="w-full rounded-xl pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($produks as $p)
                                @php($sisa = (int) (($p->qty_masuk ?? 0) - ($p->qty_keluar ?? 0)))
                                <option value="{{ $p->id }}" data-sisa="{{ $sisa }}" @selected((old('produk_id', $selectedProdukId ?? 0))==$p->id)>
                                    {{ $p->nama_produk }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div id="infoSisaMasuk" class="text-xs text-gray-500 dark:text-slate-400 mt-1 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 18a6 6 0 110-12 6 6 0 010 12z"/></svg>
                            <span>Pilih barang untuk melihat sisa stok saat ini.</span>
                        </div>
                        @error('produk_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Qty -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Qty</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10M10 7h4"/></svg>
                            </span>
                            <input id="qtyMasuk" type="number" name="qty" value="{{ old('qty', 1) }}" min="1" class="w-full rounded-xl pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        @error('qty')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tanggal</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="w-full rounded-xl pl-9 pr-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        @error('tanggal')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('barang.masuk.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800">Batal</a>
                    <button class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('produkSelectMasuk');
  const info = document.getElementById('infoSisaMasuk');
  function updateSisa() {
    const opt = select.options[select.selectedIndex];
    const sisa = parseInt(opt?.getAttribute('data-sisa') || 'NaN');
    if (!isNaN(sisa)) {
      info.textContent = 'Sisa stok saat ini: ' + sisa.toLocaleString('id-ID');
    } else {
      info.textContent = 'Pilih barang untuk melihat sisa stok saat ini.';
    }
  }
  select.addEventListener('change', updateSisa);
  updateSisa();
});
</script>
@endsection
