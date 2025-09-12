@extends('layouts.app')

@section('content')
<div class="p-6 space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Barang Keluar</h1>
        <a href="{{ route('barang.keluar.index') }}" class="inline-flex items-center px-3 py-2 rounded border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Kembali</a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 max-w-xl">
        <form method="POST" action="{{ route('barang.keluar.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm mb-1">Nama Barang</label>
                <select id="produkSelect" name="produk_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($produks as $p)
                        @php($sisa = (int) (($p->qty_masuk ?? 0) - ($p->qty_keluar ?? 0)))
                        <option value="{{ $p->id }}" data-sisa="{{ $sisa }}" @selected((old('produk_id', $selectedProdukId ?? 0))==$p->id)>
                            {{ $p->nama_produk }}
                        </option>
                    @endforeach
                </select>
                <div id="infoSisa" class="text-xs text-gray-600 dark:text-gray-300 mt-1"></div>
                @error('produk_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Qty</label>
                <input id="qtyInput" type="number" name="qty" value="{{ old('qty', 1) }}" min="1" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800" required>
                @error('qty')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800" required>
                @error('tanggal')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="pt-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('produkSelect');
  const info = document.getElementById('infoSisa');
  const qty = document.getElementById('qtyInput');
  function updateSisa() {
    const opt = select.options[select.selectedIndex];
    const sisa = parseInt(opt?.getAttribute('data-sisa') || '0');
    if (!isNaN(sisa)) {
      info.textContent = 'Sisa stok: ' + sisa.toLocaleString('id-ID');
      qty.max = Math.max(sisa, 1);
      if (parseInt(qty.value || '0') > sisa) qty.value = sisa || 1;
    } else {
      info.textContent = '';
      qty.removeAttribute('max');
    }
  }
  select.addEventListener('change', updateSisa);
  updateSisa();
});
</script>
@endsection
