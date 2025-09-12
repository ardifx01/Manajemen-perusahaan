@extends('layouts.app')

@section('content')
<div class="w-full px-2 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-5">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl p-5 flex items-center justify-between shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-slate-100">Edit Barang Masuk</h1>
                <p class="text-gray-500 dark:text-slate-400 text-sm">Ubah data stok masuk</p>
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
    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow w-full">
        <div class="bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-600 p-5">
            <div class="flex items-center gap-2 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
                <h3 class="font-semibold">Form Edit</h3>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('barang.masuk.update', $masuk) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <!-- Produk -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Nama Barang
                        </label>
                        <select name="produk_id" class="w-full rounded-xl px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" required>
                            @foreach ($produks as $p)
                                <option value="{{ $p->id }}" @selected(old('produk_id', $masuk->produk_id)==$p->id)>{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                        @error('produk_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Qty -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10M10 7h4"/></svg>
                            Qty
                        </label>
                        <input type="number" name="qty" value="{{ old('qty', $masuk->qty) }}" min="1" class="w-full rounded-xl px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" required>
                        @error('qty')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Tanggal
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', \Illuminate\Support\Carbon::parse($masuk->tanggal)->toDateString()) }}" class="w-full rounded-xl px-3 py-2 border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" required>
                        @error('tanggal')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('barang.masuk.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-800">Batal</a>
                    <button class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
