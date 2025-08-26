{{-- resources/views/dashboard/produk_index.blade.php --}}
@extends('layouts.app')
@section('title', 'DATA PRODUK')

@section('content')

<h2 class="text-xl font-bold mb-4 flex items-center gap-2">
    <span>🛒</span> Data Produk
</h2>

@if (session('success'))
    <div class="mb-4 bg-green-100 text-green-700 p-3 rounded shadow">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto bg-white rounded shadow border" x-data="{ showModal: false, produk: {} }">
    <table class="w-full table-auto">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="border px-4 py-3">#</th>
                <th class="border px-4 py-3">Nama Produk</th>
                <th class="border px-4 py-3">Harga</th>
                <th class="border px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $produk)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2 font-medium text-gray-700">{{ $produk->nama_produk }}</td>
                    <td class="border px-4 py-2 text-gray-600">{{ number_format($produk->harga) }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex items-center justify-center gap-4">
                            {{-- Tombol Edit --}}
                            <button 
                                class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold text-sm px-2 py-1 rounded hover:bg-blue-50 transition"
                                @click="
                                    produk = { 
                                        id: {{ $produk->id }}, 
                                        nama_produk: '{{ $produk->nama_produk }}', 
                                        harga: '{{ $produk->harga }}' 
                                    };
                                    showModal = true;
                                ">
                                ✏️ Edit
                            </button>

                            {{-- Separator --}}
                            <div class="w-px h-5 bg-gray-300"></div>

                            {{-- Tombol Delete --}}
                            <button 
                                class="text-red-600 hover:underline inline-flex items-center gap-1 bg-transparent border-0 p-0 m-0 cursor-pointer"
                                onclick="event.preventDefault(); if(confirm('Yakin hapus produk?')) document.getElementById('delete-produk-{{ $produk->id }}').submit();">
                                <span>🗑️</span> Delete
                            </button>

                            {{-- Form Delete --}}
                            <form id="delete-produk-{{ $produk->id }}" action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Edit Produk --}}
    <div 
        x-show="showModal" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-transition
    >
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4">Edit Produk</h3>
            <form :action="'/produk/' + produk.id" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium">Nama Produk</label>
                    <input type="text" name="nama_produk" x-model="produk.nama_produk" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Harga / PCS</label>
                    <input type="number" name="harga_pcs" x-model="produk.harga_pcs" class="w-full border rounded px-3 py-2" min="0" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Harga / SET</label>
                    <input type="number" name="harga_set" x-model="produk.harga_set" class="w-full border rounded px-3 py-2" min="0" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ url('po') }}" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
        ← Kembali ke Input PO
    </a>
</div>

{{-- Tambahkan Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>

@endsection
