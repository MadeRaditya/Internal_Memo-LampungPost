<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Internal Memo - Lampung Post</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">
    
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <i data-lucide="file-text" class="w-6 h-6 text-blue-600 mr-2"></i>
            <span class="font-bold text-lg text-gray-900">E-Memo</span>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="{{ route('inbox.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i data-lucide="inbox" class="w-5 h-5"></i> Inbox
            </a>
            <a href="{{ route('memos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i data-lucide="folder" class="w-5 h-5"></i> Memo Saya
            </a>
            <a href="{{ route('memos.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 transition-colors">
                <i data-lucide="plus-square" class="w-5 h-5"></i> Buat Memo Baru
            </a>
            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
                <div class="pt-6 pb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Pengaturan Sistem
                </div>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                    <i data-lucide="users" class="w-5 h-5"></i> Kelola Pengguna
                </a>
                <a href="{{ route('departments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                    <i data-lucide="building-2" class="w-5 h-5"></i> Kelola Divisi
                </a>
            @endif
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm flex-shrink-0">
            <div class="flex items-center gap-2 text-sm text-gray-600 font-medium">
                <i data-lucide="building" class="w-4 h-4"></i>
                {{ auth()->user()->department->name ?? 'Sistem' }}
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500 uppercase">{{ auth()->user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#1f2937'
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif
        
        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif
    </script>
</body>
</html>