@extends('layouts.app')

@section('content')
<div class="p-6 space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Barang Masuk</h1>
        <a href="{{ route('barang.masuk.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Tambah</a>
    </div>

    @if (session('success'))
        <div class="p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Nama Barang</th>
                    <th class="px-4 py-2 text-left">Qty</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($items as $row)
                    <tr>
                        <td class="px-4 py-2">{{ $row->tanggal }}</td>
                        <td class="px-4 py-2">{{ $row->produk->nama_produk ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $row->qty }}</td>
                        <td class="px-4 py-2">{{ $row->keterangan }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('barang.masuk.edit', $row) }}" class="px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('barang.masuk.destroy', $row) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $items->links() }}
    </div>
</div>
@endsection
