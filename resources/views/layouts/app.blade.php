<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>
        (function(){
            try {
                const pref = localStorage.getItem('theme');
                const d = document.documentElement;
                if (pref === 'dark' || (!pref && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    d.classList.add('dark');
                } else {
                    d.classList.remove('dark');
                }
            } catch(e) {}
        })();
    </script>
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
</head>
<body x-data="{ mobileSidebarOpen: false, desktopSidebarOpen: true }" x-init="
    (() => {
      const mq = window.matchMedia('(min-width: 768px)');
      const onChange = e => { if (e.matches) { desktopSidebarOpen = true; } };
      if (mq.addEventListener) { mq.addEventListener('change', onChange); } else { mq.addListener(onChange); }
    })();
  " class="flex font-sans bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-100">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 min-h-screen flex flex-col transform transition-transform duration-300 fixed inset-y-0 left-0 z-40 -translate-x-full md:fixed md:inset-y-0 md:left-0"
           x-bind:class="{
                '-translate-x-full': !mobileSidebarOpen,
                'translate-x-0': mobileSidebarOpen,
                'md:translate-x-0': desktopSidebarOpen,
                'md:-translate-x-full': !desktopSidebarOpen
           }">
        <div class="h-16 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 md:justify-center md:px-0">
            <img src="{{ asset('image/cam.png') }}" alt="Logo Perusahaan" class="h-10">
            <!-- Close (mobile) using hamburger icon on the right -->
            <button type="button" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    @click="mobileSidebarOpen = false; window.dispatchEvent(new Event('forcechartresize'))" aria-label="Tutup sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <nav class="flex-1 p-6 space-y-2 text-lg font-medium" @click="$event.target.closest('a') && (mobileSidebarOpen=false)"
            x-data="{ open: {{ request()->routeIs('produk.*') || request()->routeIs('kendaraan.*') || request()->routeIs('customer.*') || request()->routeIs('pengirim.*') || request()->routeIs('po') || request()->routeIs('po.*') ? 'true' : 'false' }}, employeeOpen: {{ request()->routeIs('employee.*') || request()->routeIs('salary.*') ? 'true' : 'false' }}, userOpen: {{ request()->routeIs('users.*') || request()->routeIs('settings') ? 'true' : 'false' }} }">

            <a href="{{ route('dashboard') }}"
               class="group flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Moved Karyawan menu to be positioned right after Dashboard -->
            <!-- Menambahkan menu Karyawan dengan submenu -->
            <button @click="employeeOpen = !employeeOpen"
                    class="w-full text-left px-4 py-2 rounded-lg transition-all duration-200 flex justify-between items-center {{ request()->routeIs('employee.*') || request()->routeIs('salary.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <span class="inline-flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm0 2c-2.761 0-5 2.015-5 4.5V20h10v-2.5c0-2.485-2.239-4.5-5-4.5zm8 0c-.725 0-1.414.131-2.047.364 1.22.903 2.047 2.235 2.047 3.886V20h6v-2.75c0-2.351-2.239-4.25-6-4.25z"/>
                    </svg>
                    Karyawan
                </span>
                <svg x-bind:class="{ 'rotate-90': employeeOpen }" class="w-4 h-4 transition-transform duration-300 transform"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Submenu Karyawan -->
            <div x-show="employeeOpen" x-transition.duration.300ms class="ml-6 pl-2 border-l border-gray-300 dark:border-gray-700 space-y-1 text-base overflow-hidden">
                <a href="{{ route('employee.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('employee.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Data Karyawan</span>
                </a>

                <a href="{{ route('salary.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('salary.*') ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-3.866 0-7 2.015-7 4.5S8.134 17 12 17s7-2.015 7-4.5S15.866 8 12 8zm0 0V5m0 12v2"/></svg>
                    <span>Gaji Karyawan</span>
                </a>
            </div>

            <!-- Parent Menu with Toggle -->
            <button @click="open = !open"
                    class="w-full text-left px-4 py-2 rounded-lg transition-all duration-200 flex justify-between items-center {{ request()->routeIs('po') || request()->routeIs('po.*') || request()->routeIs('produk.*') || request()->routeIs('kendaraan.*') || request()->routeIs('customer.*') || request()->routeIs('pengirim.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <span class="inline-flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    Input PO
                </span>
                <svg x-bind:class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-300 transform"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Submenu -->
            <div x-show="open" x-transition.duration.300ms class="ml-6 pl-2 border-l border-gray-300 dark:border-gray-700 space-y-1 text-base overflow-hidden">
                {{-- Form input PO --}}
                <a href="{{ route('po.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('po') || request()->routeIs('po.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                    <span>Form input PO</span>
                </a>

                {{-- Data Customer link --}}
                <a href="{{ route('customer.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('customer.*') ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm0 2c-2.761 0-5 2.015-5 4.5V20h10v-2.5c0-2.485-2.239-4.5-5-4.5z"/></svg>
                    <span>Data Customer</span>
                </a>

                {{-- Data Pengirim menu item with purple color scheme --}}
                <a href="{{ route('pengirim.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('pengirim.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v4H3zM3 7l3 13h12l3-13"/></svg>
                    <span>Data Pengirim</span>
                </a>

                <a href="{{ route('produk.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('produk.*') ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/></svg>
                    <span>Data Produk</span>
                </a>

                <a href="{{ route('kendaraan.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('kendaraan.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-2 3-3h6l3 3 2 2v6h-2a2 2 0 01-4 0H9a2 2 0 01-4 0H3v-6z"/></svg>
                    <span>Data Kendaraan</span>
                </a>
            </div>

            <a href="{{ route('suratjalan.index') }}"
               class="group flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('suratjalan.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M8 15h6M6 19h12a2 2 0 002-2V7a2 2 0 00-2-2H9l-3 3v11a2 2 0 002 2z"/></svg>
                <span>Data PO</span>
            </a>

            <a href="{{ route('tanda-terima.index') }}"
               class="group flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('tanda-terima.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>Tanda Terima</span>
            </a>

            <a href="{{ route('jatuh-tempo.index') }}"
               class="group flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('jatuh-tempo.*') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"/></svg>
                <span>Jatuh Tempo</span>
            </a>

            <!-- Collapsible: Manajemen Pengguna -->
            <button @click="userOpen = !userOpen"
                    class="w-full text-left px-4 py-2 rounded-lg transition-all duration-200 flex justify-between items-center {{ request()->routeIs('users.*') || request()->routeIs('settings') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <span class="inline-flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19a7 7 0 0114 0"/>
                    </svg>
                    Manajemen Pengguna
                </span>
                <svg x-bind:class="{ 'rotate-90': userOpen }" class="w-5 h-5 transition-transform duration-300 transform"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div id="user-section" x-show="userOpen" x-transition.duration.300ms class="ml-6 pl-2 border-l border-gray-300 dark:border-gray-700 space-y-1 text-base overflow-hidden">
                <a href="{{ Route::has('users.create') ? route('users.create') : url('/users/create') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('users.create') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19a7 7 0 0114 0"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6m3-3h-6"/>
                    </svg>
                    <span>Tambah User</span>
                </a>

                @if(auth()->user()?->is_admin)
                <a href="{{ route('users.index') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('users.index') ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V9H2v11h5M7 9V7a5 5 0 0110 0v2"/>
                    </svg>
                    <span>Daftar User</span>
                </a>
                @endif

                <a href="{{ route('settings') }}"
                   class="group flex items-center gap-2 px-3 py-1 rounded transition-all duration-200 {{ request()->routeIs('settings') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.115c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.116 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.116 2.572c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.116c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.116c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.116-2.572c-1.756-.426-1.756-2.924 0-3.35.53-.128.96-.558 1.116-1.116.94-1.543 3.31-.826 2.37 2.37a1.724 1.724 0 00-2.573 1.116z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar bottom: User + Logout -->
        <div class="mt-auto px-4 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-10 w-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" x-data="{ running:false }">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center h-9 w-9 rounded-md bg-rose-600 text-white hover:bg-rose-700 transition transform hover:translate-x-0.5"
                            :class="running ? 'opacity-80 cursor-wait' : ''"
                            title="Keluar"
                            @click.prevent="running = true; setTimeout(() => $el.closest('form').submit(), 350)">
                        <template x-if="!running">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                        </template>
                        <template x-if="running">
                            <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </template>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- Overlay for mobile sidebar -->
    <div x-show="mobileSidebarOpen" class="fixed inset-0 z-30 bg-black/40 md:hidden" @click="mobileSidebarOpen=false" x-transition.opacity></div>

    <!-- Floating handle to show sidebar on desktop when hidden -->
    <button type="button" x-show="!desktopSidebarOpen" class="hidden md:flex fixed top-1/2 -translate-y-1/2 left-2 z-50 h-10 w-10 items-center justify-center rounded-md bg-white/90 dark:bg-gray-800/90 backdrop-blur shadow border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            @click="desktopSidebarOpen = true" aria-label="Tampilkan sidebar" title="Tampilkan sidebar" x-transition.opacity>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Floating handle to hide sidebar on desktop when visible -->
    <button type="button" x-show="desktopSidebarOpen" class="hidden md:flex fixed top-1/2 -translate-y-1/2 left-64 translate-x-2 z-50 h-10 w-10 items-center justify-center rounded-md bg-white/90 dark:bg-gray-800/90 backdrop-blur shadow border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            @click="desktopSidebarOpen = false" aria-label="Sembunyikan sidebar" title="Sembunyikan sidebar" x-transition.opacity>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:ml-64" x-bind:class="{ 'md:ml-64': desktopSidebarOpen, 'md:ml-0': !desktopSidebarOpen }">
        <header class="sticky top-0 z-30 h-16 bg-white/90 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800 px-4 md:px-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Hamburger (mobile only) toggles open/close -->
                <button type="button" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        @click="mobileSidebarOpen = !mobileSidebarOpen" aria-controls="sidebar" :aria-expanded="mobileSidebarOpen.toString()" aria-label="Toggle sidebar">
                    <!-- Icon: hamburger when closed -->
                    <svg x-show="!mobileSidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <!-- Icon: close when open -->
                    <svg x-show="mobileSidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h1 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-100">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-1 md:gap-2">
                <button id="theme-toggle" title="Toggle Tema" class="relative flex items-center w-[3.75rem] h-8 rounded-full bg-gray-200 dark:bg-gray-700 p-1 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute top-1 left-1 flex items-center justify-center h-6 w-6 rounded-full bg-white shadow-md transform transition-transform duration-300 dark:translate-x-7"></span>
                    <span class="relative z-10 w-6 h-6 flex items-center justify-center">
                        <i class="fa-solid fa-moon text-gray-400 dark:text-indigo-500"></i>
                    </span>
                    <span class="relative z-10 w-6 h-6 flex items-center justify-center">
                        <i class="fa-solid fa-sun text-yellow-500 dark:text-gray-400"></i>
                    </span>
                </button>
            </div>
        </header>

        <main class="p-10 bg-white dark:bg-gray-900">
            @yield('content')
        </main>
    </div>

    <script>
        (function(){
            const btn = document.getElementById('theme-toggle');
            if (!btn) return;
            btn.addEventListener('click', function(){
                const d = document.documentElement;
                const willDark = !d.classList.contains('dark');
                d.classList.toggle('dark', willDark);
                try { localStorage.setItem('theme', willDark ? 'dark' : 'light'); } catch(e) {}
                try { window.dispatchEvent(new CustomEvent('themechange', { detail: { dark: willDark } })); } catch(e) {}
            });
        })();
    </script>

    

    @stack('scripts')

</body>
</html>
