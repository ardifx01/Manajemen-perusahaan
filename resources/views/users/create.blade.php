@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah User</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat akun pengguna baru untuk mengakses aplikasi.</p>
    </div>

    @if (session('status') === 'user-created')
        <div class="rounded-md bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-2 mb-4">
            User berhasil dibuat.
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm p-6">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="mt-1 block w-full rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400"
                       placeholder="Nama lengkap">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="mt-1 block w-full rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400"
                       placeholder="nama@email.com">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400"
                       placeholder="Ulangi password">
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">Simpan</button>
                <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-gray-400 dark:border-gray-500 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
