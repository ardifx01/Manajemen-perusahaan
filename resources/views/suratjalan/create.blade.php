@extends('layouts.app')

@section('title', 'Tambah Surat Jalan')

@section('content')
<div class="w-full px-4 md:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Tambah Surat Jalan</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('surat-jalan.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label for="tanggal_po" class="block font-semibold mb-1">Tanggal PO</label>
            <input type="date" name="tanggal_po" id="tanggal_po" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="customer" class="block font-semibold mb-1">Customer</label>
            <input type="text" name="customer" id="customer" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="no_surat_jalan" class="block font-semibold mb-1">No Surat Jalan</label>
            <input type="text" name="no_surat_jalan" id="no_surat_jalan" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="no_po" class="block font-semibold mb-1">No PO</label>
            <input type="text" name="no_po" id="no_po" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="kendaraan" class="block font-semibold mb-1">Kendaraan</label>
            <input type="text" name="kendaraan" id="kendaraan" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="no_polisi" class="block font-semibold mb-1">No Polisi</label>
            <input type="text" name="no_polisi" id="no_polisi" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="qty" class="block font-semibold mb-1">Qty</label>
            <input type="number" name="qty" id="qty" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="qty_jenis" class="block font-semibold mb-1">Jenis Qty</label>
            <input type="text" name="qty_jenis" id="qty_jenis" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="produk_id" class="block font-semibold mb-1">Produk</label>
            <select name="produk_id" id="produk_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Pilih Produk --</option>
                @foreach ($produk as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
