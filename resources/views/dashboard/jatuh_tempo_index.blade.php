@extends('layouts.app')

@section('title', 'Jatuh Tempo Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Jatuh Tempo Management</h1>
        <button onclick="openCreateModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium">
            Tambah Jatuh Tempo
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Tagihan</p>
                    <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($totalTagihan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Total Terbayar</p>
                    <p class="text-2xl font-bold text-green-900">Rp {{ number_format($totalTerbayar ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <div class="flex items-center">
                <div class="p-2 bg-red-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Total Overdue</p>
                    <p class="text-2xl font-bold text-red-900">Rp {{ number_format($totalOverdue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Jumlah Overdue</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $countOverdue ?? 0 }} Invoice</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jatuh Tempo Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Invoice</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Invoice</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tagihan</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terbayar</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari Terlambat</th>
                        <th class="py-1 px-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jatuhTempos as $jt)
                        <tr class="hover:bg-gray-50 {{ $jt->is_overdue ? 'bg-red-50' : '' }}">
                            <td class="py-1 px-1.5 text-xs">{{ $jt->no_invoice }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ Str::limit($jt->customer, 15) }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ $jt->tanggal_invoice->format('d/m/Y') }}</td>
                            <td class="py-1 px-1.5 text-xs">{{ $jt->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                            <td class="py-1 px-1.5 text-xs">Rp {{ number_format($jt->jumlah_tagihan, 0, ',', '.') }}</td>
                            <td class="py-1 px-1.5 text-xs">Rp {{ number_format($jt->jumlah_terbayar, 0, ',', '.') }}</td>
                            <td class="py-1 px-1.5 text-xs">Rp {{ number_format($jt->sisa_tagihan, 0, ',', '.') }}</td>
                            <td class="py-1 px-1.5 text-xs">
                                <span class="px-1 py-0.5 text-xs rounded-full
                                    @if($jt->status_color == 'green') bg-green-100 text-green-800
                                    @elseif($jt->status_color == 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($jt->status_color == 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $jt->status_pembayaran }}
                                </span>
                            </td>
                            <td class="py-1 px-1.5 text-xs">
                                @if($jt->hari_terlambat > 0)
                                    <span class="text-red-600 font-semibold">{{ $jt->hari_terlambat }} hari</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="py-1 px-1.5 text-xs space-x-1">
                                <button onclick="editJatuhTempo({{ $jt->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                                @if($jt->is_overdue && !$jt->reminder_sent)
                                    <a href="{{ route('jatuh-tempo.send-reminder', $jt->id) }}" class="text-orange-600 hover:text-orange-900">Reminder</a>
                                @endif
                                <form method="POST" action="{{ route('jatuh-tempo.destroy', $jt) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-4 px-6 text-center text-gray-500">Belum ada data jatuh tempo</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="jatuhTempoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-lg font-medium">Tambah Jatuh Tempo</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="jatuhTempoForm" method="POST" action="{{ route('jatuh-tempo.store') }}" class="space-y-4">
                    @csrf
                    <div id="methodField"></div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Invoice</label>
                            <input type="text" name="no_invoice" id="no_invoice" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No PO</label>
                            <input type="text" name="no_po" id="no_po" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <input type="text" name="customer" id="customer" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Invoice</label>
                            <input type="date" name="tanggal_invoice" id="tanggal_invoice" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Tagihan</label>
                            <input type="number" name="jumlah_tagihan" id="jumlah_tagihan" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Terbayar</label>
                            <input type="number" name="jumlah_terbayar" id="jumlah_terbayar" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Denda</label>
                            <input type="number" name="denda" id="denda" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                        <select name="status_pembayaran" id="status_pembayaran" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="Belum Bayar">Belum Bayar</option>
                            <option value="Sebagian">Sebagian</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
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
    document.getElementById('modalTitle').textContent = 'Tambah Jatuh Tempo';
    document.getElementById('jatuhTempoForm').action = '{{ route("jatuh-tempo.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('jatuhTempoForm').reset();
    document.getElementById('jatuhTempoModal').classList.remove('hidden');
}

function editJatuhTempo(id) {
    document.getElementById('modalTitle').textContent = 'Edit Jatuh Tempo';
    document.getElementById('jatuhTempoForm').action = `/jatuh-tempo/${id}`;
    document.getElementById('methodField').innerHTML = '@method("PUT")';
    document.getElementById('jatuhTempoModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('jatuhTempoModal').classList.add('hidden');
}
</script>
@endsection
