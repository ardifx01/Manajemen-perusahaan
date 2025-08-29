@extends('layouts.app')

@section('title', 'Data Pengirim')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  .font-inter{font-family:'Inter',ui-sans-serif,system-ui,-apple-system,'Segoe UI',Roboto,Helvetica,Arial,'Apple Color Emoji','Segoe UI Emoji'}
  .hover-card:hover{box-shadow:0 10px 20px -10px rgba(2,6,23,0.2)}
  .divider{border-color:rgba(17,24,39,.08)}
  .badge{display:inline-flex;align-items:center;border-radius:9999px;padding:.25rem .5rem;font-weight:600;font-size:.75rem}
  .badge-blue{background:#DBEAFE;color:#1E40AF}
</style>
@endpush

@section('content')
<div class="w-full px-4 md:px-6 lg:px-8 py-6 font-inter">
    <!-- Header - konsisten biru/putih -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-xl shadow-lg p-4 md:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl md:text-3xl font-bold text-gray-900 dark:text-slate-100">Data Pengirim</h1>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 text-sm md:text-base">Kelola informasi pengirim</p>
                </div>
            </div>
            <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold shadow-md transition-all duration-200 flex items-center justify-center gap-2 text-sm md:text-base w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-blue-200">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Tambah Pengirim</span>
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

    <!-- Toolbar: Search only (no dropdown) -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-xl shadow-sm p-4 mb-4">
        <div class="grid grid-cols-1 gap-3">
            <div>
                <label class="sr-only" for="searchInput">Cari</label>
                <div class="relative">
                    <input id="searchInput" type="text" placeholder="Cari pengirim... (nama)" class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table (desktop/tablet) -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hidden md:block">
        <div class="overflow-x-auto" id="tableContainer">
            <table class="min-w-full table-auto" id="pengirimTable">
                <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-700 dark:text-slate-200">
                    <tr>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm border-b divider">No</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-left font-semibold text-xs md:text-sm min-w-[180px] border-b divider">Nama Pengirim</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-center font-semibold text-xs md:text-sm min-w-[140px] border-b divider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($pengirims as $index => $item)
                    <tr class="hover:bg-blue-50/70 dark:hover:bg-slate-700 transition-colors duration-200" data-name="{{ Str::lower($item->nama) }}">
                        <td class="px-3 md:px-6 py-3 md:py-4 text-gray-900 dark:text-slate-200 font-medium text-sm">{{ $index + 1 }}</td>
                        <td class="px-3 md:px-6 py-3 md:py-4">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <div class="bg-blue-50 dark:bg-slate-700 p-1 md:p-2 rounded-full">
                                    <svg class="w-3 h-3 md:w-4 md:h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 dark:text-slate-200 font-medium text-sm">{{ $item->nama }}</span>
                            </div>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4">
                            <div class="flex justify-center gap-2">
                                <x-table.action-buttons 
                                    onEdit="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                    deleteAction="{{ route('pengirim.destroy', $item->id) }}"
                                    confirmText="Yakin ingin menghapus pengirim ini?" />
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="bg-gray-100 dark:bg-slate-700 p-6 rounded-full">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-slate-200 mb-2">Belum ada data pengirim</h3>
                                    <p class="text-gray-500 dark:text-slate-400 mb-4">Mulai dengan menambahkan pengirim pertama Anda.</p>
                                    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">Tambah Pengirim</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List View -->
    <div class="md:hidden space-y-3">
        @forelse($pengirims as $index => $item)
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-xl shadow-sm p-4 hover-card" data-name="{{ Str::lower($item->nama) }}">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">{{ $index + 1 }}</span>
                            <span class="text-gray-900 dark:text-slate-100 font-semibold">{{ $item->nama }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <x-table.action-buttons 
                            onEdit="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                            deleteAction="{{ route('pengirim.destroy', $item->id) }}"
                            confirmText="Yakin ingin menghapus pengirim ini?" />
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-xl shadow-sm p-6 text-center">
                <p class="text-gray-600 dark:text-slate-300">Belum ada data pengirim</p>
                <button onclick="openAddModal()" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Tambah Pengirim</button>
            </div>
        @endforelse
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
// Filter/Search: by name (header input)
window.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('tableContainer');

    function checkScroll() {
        if (!tableContainer) return;
        if (tableContainer.scrollWidth > tableContainer.clientWidth) {
            tableContainer.classList.add('shadow-inner');
        } else {
            tableContainer.classList.remove('shadow-inner');
        }
    }
    checkScroll();
    window.addEventListener('resize', checkScroll);

    function applyFilter() {
        const q = (searchInput?.value || '').toLowerCase().trim();

        // Table rows
        document.querySelectorAll('#pengirimTable tbody tr').forEach(tr => {
            const name = (tr.dataset.name || '').toLowerCase();
            const visible = !q || name.includes(q);
            tr.classList.toggle('hidden', !visible);
        });

        // Mobile cards
        document.querySelectorAll('.md\\:hidden .hover-card').forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const visible = !q || name.includes(q);
            card.classList.toggle('hidden', !visible);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }
    applyFilter();
});
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
