@extends('layouts.app')

@section('title', 'Tanda Terima Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tanda Terima Management</h1>
        <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
            Buat Tanda Terima Baru
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tanda Terima Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Tanda Terima</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No PO</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terima</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Dikirim</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Diterima</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tandaTerimas as $tt)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-1.5 text-xs">{{ $tt->no_tanda_terima }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ $tt->no_po }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ Str::limit($tt->customer, 15) }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ $tt->tanggal_terima->format('d/m/Y') }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ $tt->qty_dikirim }} {{ $tt->qty_jenis }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ $tt->qty_diterima }} {{ $tt->qty_jenis }}</td>
                            <td class="py-1 px-1.5 text-xs">
                                <span class="px-1 py-0.5 text-xs rounded-full
                                    @if($tt->kondisi_barang == 'Baik') bg-green-100 text-green-800
                                    @elseif($tt->kondisi_barang == 'Sebagian Rusak') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $tt->kondisi_barang }}
                                </span>
                            </td>
                            <td class="py-1 px-1.5 text-xs">
                                <span class="px-1 py-0.5 text-xs rounded-full
                                    @if($tt->status == 'Lengkap') bg-green-100 text-green-800
                                    @elseif($tt->status == 'Sebagian') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $tt->status }}
                                </span>
                            </td>
                            <td class="py-1 px-1.5 text-xs">{{ Str::limit($tt->penerima_nama, 12) }}</td>
                            <td class="py-1 px-1.5 text-xs space-x-1">
                                <button onclick="editTandaTerima({{ $tt->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                                <form method="POST" action="{{ route('tanda-terima.destroy', $tt) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-4 px-6 text-center text-gray-500">Belum ada data tanda terima</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="tandaTerimaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-lg font-medium">Buat Tanda Terima Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="tandaTerimaForm" method="POST" action="{{ route('tanda-terima.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div id="methodField"></div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Tanda Terima</label>
                            <input type="text" name="no_tanda_terima" id="no_tanda_terima" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No PO</label>
                            <input type="text" name="no_po" id="no_po" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Surat Jalan</label>
                            <input type="text" name="no_surat_jalan" id="no_surat_jalan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <input type="text" name="customer" id="customer" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat 1</label>
                            <input type="text" name="alamat_1" id="alamat_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat 2</label>
                            <input type="text" name="alamat_2" id="alamat_2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                            <input type="date" name="tanggal_terima" id="tanggal_terima" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="produk_id" id="produk_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Qty Dikirim</label>
                            <input type="number" name="qty_dikirim" id="qty_dikirim" required min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Qty Diterima</label>
                            <input type="number" name="qty_diterima" id="qty_diterima" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis</label>
                            <select name="qty_jenis" id="qty_jenis" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="PCS">PCS</option>
                                <option value="SET">SET</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kondisi Barang</label>
                            <select name="kondisi_barang" id="kondisi_barang" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Sebagian Rusak">Sebagian Rusak</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="Lengkap">Lengkap</option>
                                <option value="Sebagian">Sebagian</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                            <input type="text" name="penerima_nama" id="penerima_nama" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jabatan Penerima</label>
                            <input type="text" name="penerima_jabatan" id="penerima_jabatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Bukti</label>
                        <input type="file" name="foto_bukti" id="foto_bukti" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Buat Tanda Terima Baru';
    document.getElementById('tandaTerimaForm').action = '{{ route("tanda-terima.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('tandaTerimaForm').reset();
    document.getElementById('tandaTerimaModal').classList.remove('hidden');
}

function editTandaTerima(id) {
    document.getElementById('modalTitle').textContent = 'Edit Tanda Terima';
    document.getElementById('tandaTerimaForm').action = `/tanda-terima/${id}`;
    document.getElementById('methodField').innerHTML = '@method("PUT")';
    document.getElementById('tandaTerimaModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('tandaTerimaModal').classList.add('hidden');
}
</script>
@endsection
