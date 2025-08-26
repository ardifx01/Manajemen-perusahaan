
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold">Dashboard</div>
    <div class="flex items-center gap-6">
        <span class="text-sm text-gray-600">{{ Auth::user()->email }}</span>

        <!-- Logout button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Logout" class="hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-700 hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                </svg>
            </button>
        </form>

        <!-- Avatar -->
        <div class="h-8 w-8 rounded-full bg-blue-700 text-white flex items-center justify-center text-sm font-semibold">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
    </div>
</nav>
