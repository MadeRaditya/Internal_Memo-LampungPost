<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Internal Memo' }} - Lampung Post</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        /* Custom scrollbar for desktop views */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full overflow-hidden text-slate-800 antialiased flex bg-slate-50">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar(false)" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside id="app-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 flex flex-col transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0 md:static md:flex-shrink-0 shadow-sm">
        <!-- Logo / Brand Header -->
        <div class="h-16 px-6 border-b border-slate-100 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-base font-extrabold text-slate-900 tracking-tight block leading-tight">E-MEMO</span>
                    <span class="text-xs font-medium text-slate-400">Lampung Post</span>
                </div>
            </a>
            <button onclick="toggleSidebar(false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 md:hidden" aria-label="Tutup Menu">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Primary Action: Buat Memo Baru -->
        <div class="px-5 pt-5 pb-2">
            <a href="{{ route('memos.create') }}" class="flex items-center justify-center gap-2.5 w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition-all shadow-sm shadow-blue-500/25">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Buat Memo Baru</span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-3 space-y-6 overflow-y-auto">
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Utama</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('inbox.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('inbox.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="inbox" class="w-4 h-4 {{ request()->routeIs('inbox.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                            <span>Kotak Masuk</span>
                        </div>
                    </a>

                    <a href="{{ route('memos.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('memos.index') || request()->routeIs('memos.show') || request()->routeIs('memos.edit') ? 'bg-blue-50 text-blue-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i data-lucide="send" class="w-4 h-4 {{ request()->routeIs('memos.index') || request()->routeIs('memos.show') || request()->routeIs('memos.edit') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Memo Keluar</span>
                    </a>
                </div>
            </div>

            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengaturan Sistem</p>
                <div class="space-y-1">
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('users.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Kelola Pengguna</span>
                    </a>

                    <a href="{{ route('departments.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('departments.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i data-lucide="building-2" class="w-4 h-4 {{ request()->routeIs('departments.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Kelola Divisi</span>
                    </a>
                </div>
            </div>
            @endif
        </nav>

        <!-- Sidebar Footer / User Profile Summary -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 border border-blue-200 text-blue-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->department->name ?? 'Divisi Umum' }}</p>
                </div>
                <button type="button" onclick="confirmLogout()" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Keluar dari sistem" aria-label="Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </div>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
        <!-- Top App Header -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between flex-shrink-0 shadow-xs z-30">
            <!-- Left Header: Mobile Toggle & Context Badge -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar(true)" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 md:hidden focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Buka Menu">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100/80 text-xs font-medium text-slate-700 border border-slate-200/60">
                    <i data-lucide="building" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>{{ auth()->user()->department->name ?? 'Lampung Post' }}</span>
                </div>
            </div>

            <!-- Right Header: User Info & Actions -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <div class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</div>
                    <div class="inline-flex items-center gap-1 text-[10px] font-semibold tracking-wide text-blue-600 uppercase bg-blue-50 px-1.5 py-0.5 rounded">
                        {{ auth()->user()->role }}
                    </div>
                </div>

                <div class="h-7 w-px bg-slate-200 hidden sm:block"></div>

                <button type="button" onclick="confirmLogout()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-lg transition-all" title="Keluar dari akun">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </div>
        </header>

        <!-- Main Body Scrollable View -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <!-- Flash Message Banner (Optional Fallback / Validation Summary) -->
            @if ($errors->any())
                <div class="max-w-6xl mx-auto mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs flex items-start gap-3">
                    <div class="p-1 rounded-full bg-rose-100 text-rose-600 flex-shrink-0 mt-0.5">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-rose-900 mb-1">Terdapat kesalahan pada input formulir:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Application Global Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Responsive Sidebar Toggle
        function toggleSidebar(open) {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (open) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#0f172a',
            customClass: {
                popup: 'shadow-lg border border-slate-200 rounded-xl'
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif
        
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // Standardized SweetAlert2 Logout Confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sistem E-Memo?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-sm font-semibold px-4 py-2',
                    cancelButton: 'rounded-xl text-sm font-semibold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Global Helper for Delete / Destructive Action Modals
        function confirmDelete(event, form, message = 'Data yang dihapus tidak dapat dipulihkan!') {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-sm font-semibold px-4 py-2',
                    cancelButton: 'rounded-xl text-sm font-semibold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        // Global Helper for Status Toggle Modals
        function confirmToggleStatus(event, form, message = 'Apakah Anda yakin ingin mengubah status?') {
            event.preventDefault();
            Swal.fire({
                title: 'Ubah Status',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-sm font-semibold px-4 py-2',
                    cancelButton: 'rounded-xl text-sm font-semibold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
</body>
</html>