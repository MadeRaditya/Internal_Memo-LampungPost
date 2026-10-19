<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Internal Memo Lampung Post</title>

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
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 sm:p-6 bg-radial from-slate-100 via-slate-100 to-slate-200">

    <div class="w-full max-w-md">
        <!-- Brand / Identity Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-8 sm:p-10 space-y-7">
            <!-- Header Logo & Heading -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-500/25 mb-1">
                    <i data-lucide="file-text" class="w-7 h-7"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">E-MEMO</h1>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Lampung Post Media Group</p>
                <p class="text-sm text-slate-500 pt-1">Silakan masuk menggunakan akun internal Anda</p>
            </div>

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Input Field -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-700 tracking-wide uppercase">
                        Alamat Email <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('email') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 placeholder:text-slate-400 outline-none transition-all"
                               placeholder="nama@lampost.co.id"
                               autocomplete="email">
                    </div>
                    @error('email')
                        <p class="text-xs font-medium text-rose-600 flex items-center gap-1 mt-1">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Password Input Field with Toggle Visibility -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-slate-700 tracking-wide uppercase">
                            Kata Sandi <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-10 pr-11 py-2.5 bg-slate-50 border @error('password') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 placeholder:text-slate-400 outline-none transition-all"
                               placeholder="••••••••"
                               autocomplete="current-password">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Lihat kata sandi">
                            <i id="eye-icon" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs font-medium text-rose-600 flex items-center gap-1 mt-1">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 cursor-pointer mt-2">
                    <span id="btnText">Masuk Ke Sistem</span>
                    <i id="btnIcon" data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400">
                    Sistem Tata Kelola Surat & Memo Internal • &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Password visibility toggle
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // Double submit prevention and button loading feedback
        const loginForm = document.getElementById('loginForm');
        let isSubmitting = false;

        loginForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.textContent = 'Memproses Masuk...';
            btnIcon.setAttribute('data-lucide', 'loader-2');
            btnIcon.classList.add('animate-spin');
            lucide.createIcons();
        });

        // Flash message handling
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal Masuk',
                text: "{{ session('error') }}",
                confirmButtonColor: '#2563eb',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-sm font-semibold px-5 py-2.5'
                }
            });
        @endif
    </script>
</body>
</html>