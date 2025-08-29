@extends('layouts.app')

@section('content')
<div class="p-6 space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Barang Keluar</h1>
        <a href="{{ route('barang.keluar.index') }}" class="inline-flex items-center px-3 py-2 rounded border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Kembali</a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 max-w-xl">
        <form method="POST" action="{{ route('barang.keluar.update', $keluar) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm mb-1">Nama Barang</label>
                <select name="produk_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800" required>
                    @foreach ($produks as $p)
                        <option value="{{ $p->id }}" @selected(old('produk_id', $keluar->produk_id)==$p->id)>{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
                @error('produk_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Qty</label>
                <input type="number" name="qty" value="{{ old('qty', $keluar->qty) }}" min="1" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800" required>
                @error('qty')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', \Illuminate\Support\Carbon::parse($keluar->tanggal)->toDateString()) }}" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800" required>
                @error('tanggal')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800">{{ old('keterangan', $keluar->keterangan) }}</textarea>
                @error('keterangan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="pt-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
