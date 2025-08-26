@extends('layouts.app')
@section('title', 'EDIT KENDARAAN')
@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4 text-red-600">Edit Kendaraan</h1>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        @include('kendaraan.form') {{-- Form dipisah untuk reusability --}}

        <div class="flex gap-2">
            <a href="{{ route('kendaraan.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Update</button>
        </div>
    </form>
</div>
@endsection
