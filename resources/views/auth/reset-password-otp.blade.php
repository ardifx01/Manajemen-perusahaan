<x-guest-layout>
    <div class="min-h-screen flex items-center px-4 md:px-6 bg-gradient-to-br from-indigo-50 via-white to-rose-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
        <div class="mx-auto w-full max-w-md bg-white/80 dark:bg-gray-900/60 backdrop-blur rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-8">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Reset Password</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    OTP berhasil diverifikasi untuk <strong>{{ $email }}</strong>. Silakan masukkan password baru Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.reset.otp') }}" class="space-y-5">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" value="Password Baru" />
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full h-11" 
                        type="password" 
                        name="password" 
                        required 
                        autofocus 
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input 
                        id="password_confirmation" 
                        class="block mt-1 w-full h-11" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Syarat Password:</h4>
                    <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• Minimal 8 karakter</li>
                        <li>• Kombinasi huruf dan angka</li>
                        <li>• Disarankan menggunakan karakter khusus</li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <x-primary-button class="w-full justify-center">
                        Reset Password
                    </x-primary-button>
                    
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-500 dark:text-gray-400 font-medium">
                            Kembali ke Login
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const confirmPassword = document.getElementById('password_confirmation');
            
            // Simple password strength check
            if (password.length >= 8) {
                e.target.classList.remove('border-red-300');
                e.target.classList.add('border-green-300');
            } else {
                e.target.classList.remove('border-green-300');
                e.target.classList.add('border-red-300');
            }
        });

        // Password confirmation check
        document.getElementById('password_confirmation').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = e.target.value;
            
            if (password === confirmPassword && password.length > 0) {
                e.target.classList.remove('border-red-300');
                e.target.classList.add('border-green-300');
            } else {
                e.target.classList.remove('border-green-300');
                e.target.classList.add('border-red-300');
            }
        });
    </script>
</x-guest-layout>
