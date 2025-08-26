<x-guest-layout>
    <div class="min-h-screen flex items-center px-4 md:px-6 bg-gradient-to-br from-indigo-50 via-white to-rose-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
        <div class="mx-auto w-full max-w-md bg-white/80 dark:bg-gray-900/60 backdrop-blur rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-8">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Reset Password</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full h-11" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button>
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
