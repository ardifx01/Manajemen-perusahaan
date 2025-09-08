<x-guest-layout>
    <div class="min-h-screen flex items-center px-4 md:px-6 bg-gradient-to-br from-indigo-50 via-white to-rose-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
        <div class="mx-auto w-full max-w-md bg-white/80 dark:bg-gray-900/60 backdrop-blur rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-8">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Verifikasi OTP</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Kami telah mengirimkan kode OTP ke email <strong>{{ $email }}</strong>. Masukkan kode 6 digit yang Anda terima.
                </p>
            </div>

            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-5">
                @csrf

                <!-- OTP Code -->
                <div>
                    <x-input-label for="otp_code" value="Kode OTP" />
                    <x-text-input 
                        id="otp_code" 
                        class="block mt-1 w-full h-11 text-center text-2xl font-mono tracking-widest" 
                        type="text" 
                        name="otp_code" 
                        maxlength="6" 
                        placeholder="000000"
                        required 
                        autofocus 
                        autocomplete="off"
                    />
                    <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
                </div>

                <div class="space-y-3">
                    <x-primary-button class="w-full justify-center">
                        Verifikasi OTP
                    </x-primary-button>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Tidak menerima kode? 
                            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 font-medium">
                                Kirim ulang
                            </a>
                        </p>
                    </div>
                </div>
            </form>

            <!-- Timer countdown -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kode akan kedaluwarsa dalam: <span id="countdown" class="font-mono font-bold text-red-600">10:00</span>
                </p>
            </div>
        </div>
    </div>

    <script>
        // OTP input formatting
        document.getElementById('otp_code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            e.target.value = value;
        });

        // Countdown timer (10 minutes)
        let timeLeft = 10 * 60; // 10 minutes in seconds
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                countdownElement.textContent = 'Kedaluwarsa';
                countdownElement.classList.add('text-red-600');
            } else {
                timeLeft--;
            }
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
    </script>
</x-guest-layout>
