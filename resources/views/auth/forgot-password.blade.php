<x-guest-layout>
    <div class="min-h-screen flex items-center px-4 md:px-6 bg-gradient-to-br from-indigo-50 via-white to-rose-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
        <div class="mx-auto w-full max-w-md">
            <div class="relative bg-white/90 dark:bg-gray-900/60 backdrop-blur rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 p-8">
                <!-- Header / Brand -->
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('image/LOGO.png') }}" alt="Logo" class="h-8 w-8 rounded-md shadow-sm hidden sm:block" onerror="this.style.display='none'">
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Reset Password</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Masukkan email Anda, kami akan mengirimkan kode OTP untuk mengatur ulang password.</p>
                    </div>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('otp.send') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full h-11" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Action Bar: Back + Submit -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 h-10 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 111.414 1.414L7.414 9H17a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            <span>Kembali</span>
                        </a>

                        <x-primary-button>
                            Kirim Kode OTP
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Small helper note -->
            <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-4">Pastikan email aktif agar kode OTP dapat diterima.</p>
        </div>
    </div>
  </x-guest-layout>
