@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Daftar User</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hanya admin yang dapat melihat halaman ini.</p>
    </div>

    @if (session('status') === 'user-deleted')
        <div class="rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-2 mb-4">User berhasil dihapus.</div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2 mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Admin</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Dibuat</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $i => $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ ($users->currentPage()-1)*$users->perPage() + $i + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">Ya</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-300">Tidak</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-700 dark:text-gray-300">{{ $user->created_at?->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini? Tindakan tidak dapat dibatalkan.')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1.5 rounded-md text-white bg-rose-600 hover:bg-rose-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
