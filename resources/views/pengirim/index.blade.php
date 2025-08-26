@extends('layouts.app')

@section('title', 'Data Pengirim')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 dark:from-slate-800 dark:to-slate-700 rounded-lg shadow-lg p-4 md:p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between">
            <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                <div class="bg-white/20 dark:bg-white/10 p-2 md:p-3 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">Data Pengirim</h1>
                    <p class="text-purple-100 text-sm sm:text-base">Kelola informasi pengirim</p>
                </div>
            </div>
            <button onclick="openAddModal()" class="bg-white text-purple-600 dark:bg-slate-700 dark:text-purple-200 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 dark:hover:bg-slate-600 transition-all duration-200 shadow-lg focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-purple-300 focus:ring-opacity-50">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pengirim
            </button>
        </div>
    </div>

    <!-- Enhanced alert messages with better styling and icons -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 dark:bg-emerald-900/30 dark:border-emerald-700 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 dark:text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-700 dark:text-emerald-300 font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 dark:bg-rose-900/30 dark:border-rose-700 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 dark:text-rose-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-red-700 dark:text-rose-300 font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-red-800 dark:text-rose-300 font-medium mb-2">Terjadi kesalahan:</h3>
                    <ul class="text-red-700 dark:text-rose-300 text-sm list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-slate-700 dark:to-slate-700 text-white dark:text-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nama Pengirim</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($pengirims as $index => $item)
                    <tr class="group hover:bg-purple-50 dark:hover:bg-slate-700 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-slate-200">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-purple-100 group-hover:bg-purple-200 dark:bg-slate-700 p-2 rounded-lg mr-3 transition-colors duration-200">
                                    <svg class="w-5 h-5 text-purple-600 group-hover:text-purple-700 dark:text-purple-300 dark:group-hover:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-slate-200 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors duration-200">{{ $item->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}')" 
                                        class="bg-yellow-500 dark:bg-yellow-500 hover:bg-yellow-600 dark:hover:bg-yellow-400 text-white px-2 md:px-4 py-1 md:py-2 rounded-lg text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400"
                                        title="Edit data {{ $item->nama }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>Edit</span>
                                </button>
                                <form action="{{ route('pengirim.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-purple-600 dark:bg-purple-500 hover:bg-purple-700 dark:hover:bg-purple-400 text-white px-2 md:px-4 py-1 md:py-2 rounded-lg text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-1 shadow-md hover:shadow-lg w-full focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400"
                                            onclick="return confirm('Yakin ingin menghapus pengirim ini?')"
                                            title="Hapus data {{ $item->nama }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4-4-4m0 0L8 8l4-4 4 4z"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada data pengirim</p>
                                <p class="text-sm mb-4">Klik tombol "Tambah Pengirim" untuk menambah data</p>
                                <button onclick="openAddModal()" class="bg-blue-600 dark:bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-400 transition-colors duration-200">
                                    Tambah Pengirim Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">Tambah Pengirim</h2>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('pengirim.store') }}" method="POST" id="addForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Pengirim <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200 @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama pengirim"
                           value="{{ old('nama') }}">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAddModal()" 
                        class="px-6 py-3 text-gray-600 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-slate-400 focus:ring-opacity-50">
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn"
                        class="px-6 py-3 bg-blue-600 dark:bg-blue-500 text-white rounded-xl hover:bg-blue-700 dark:hover:bg-blue-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-opacity-50 flex items-center">
                    <span id="addBtnText">Simpan</span>
                    <svg id="addLoading" class="animate-spin ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">Edit Pengirim</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="oldNama" name="old_nama">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim <span class="text-red-500">*</span></label>
                    <input type="text" id="editNama" name="nama" required 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditModal()" 
                        class="px-6 py-3 text-gray-600 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-slate-400 focus:ring-opacity-50">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn"
                        class="px-6 py-3 bg-blue-600 dark:bg-blue-500 text-white rounded-xl hover:bg-blue-700 dark:hover:bg-blue-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-opacity-50 flex items-center">
                    <span id="editBtnText">Update</span>
                    <svg id="editLoading" class="animate-spin ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    setTimeout(() => {
        document.querySelector('#addModal input[name="nama"]').focus();
    }, 100);
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
    document.getElementById('addForm').reset();
    resetAddButton();
}

function openEditModal(id, nama) {
    document.getElementById('editForm').action = `/pengirim/${id}`;
    document.getElementById('editNama').value = nama;
    document.getElementById('oldNama').value = nama;
    
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    setTimeout(() => {
        document.getElementById('editNama').focus();
    }, 100);
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    resetEditButton();
}

document.getElementById('addForm').addEventListener('submit', function() {
    const btn = document.getElementById('addSubmitBtn');
    const text = document.getElementById('addBtnText');
    const loading = document.getElementById('addLoading');
    
    btn.disabled = true;
    text.textContent = 'Menyimpan...';
    loading.classList.remove('hidden');
});

document.getElementById('editForm').addEventListener('submit', function() {
    const btn = document.getElementById('editSubmitBtn');
    const text = document.getElementById('editBtnText');
    const loading = document.getElementById('editLoading');
    
    btn.disabled = true;
    text.textContent = 'Mengupdate...';
    loading.classList.remove('hidden');
});

function resetAddButton() {
    const btn = document.getElementById('addSubmitBtn');
    const text = document.getElementById('addBtnText');
    const loading = document.getElementById('addLoading');
    
    btn.disabled = false;
    text.textContent = 'Simpan';
    loading.classList.add('hidden');
}

function resetEditButton() {
    const btn = document.getElementById('editSubmitBtn');
    const text = document.getElementById('editBtnText');
    const loading = document.getElementById('editLoading');
    
    btn.disabled = false;
    text.textContent = 'Update';
    loading.classList.add('hidden');
}

document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
});
</script>
@endsection
