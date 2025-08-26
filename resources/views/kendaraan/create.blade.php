@extends('layouts.app')
@section('title', 'CREATE KENDARAAN')

@section('content')
<div class="p-4">
    <h2 class="text-xl font-semibold mb-4">Tambah Kendaraan</h2>

    <form action="{{ route('kendaraan.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block">Nama Kendaraan</label>
            <input type="text" name="nama_kendaraan" class="border p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block">No Polisi</label>
            <input type="text" name="no_polisi" class="border p-2 w-full">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
