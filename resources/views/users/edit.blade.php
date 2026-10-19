@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pb-12">

    <!-- Header & Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors" title="Kembali ke Daftar Pengguna" aria-label="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Edit Akun Pengguna</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Perbarui profil, divisi, peran, atau setel ulang kata sandi pengguna.</p>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <form id="userForm" method="POST" action="{{ route('users.update', $user->id) }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <!-- Section: Identitas Akun -->
        <div class="space-y-4">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-100">
                Identitas Akun
            </h2>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                    Nama Lengkap <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                       class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                    Alamat Email Perusahaan <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                       class="w-full px-4 py-2.5 bg-slate-50 border @error('email') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section: Penempatan & Peran -->
        <div class="space-y-4 pt-2">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-100">
                Penempatan Divisi & Hak Akses
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="department_id" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                        Divisi Kerja <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <select id="department_id" name="department_id" required 
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('department_id') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                        Hak Akses (Role) <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <select id="role" name="role" required 
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('role') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Biasa</option>
                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (SDM / Umum)</option>
                        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin (IT)</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section: Reset Kata Sandi (Opsional) -->
        <div class="p-4.5 rounded-xl border border-slate-200 bg-slate-50/70 space-y-2">
            <div class="flex items-center gap-2">
                <i data-lucide="key-round" class="w-4 h-4 text-slate-500"></i>
                <label for="password" class="text-xs font-bold text-slate-700 tracking-wide uppercase">
                    Ubah Kata Sandi (Opsional)
                </label>
            </div>
            <input type="password" id="password" name="password" 
                   placeholder="Kosongkan jika tidak ingin mengubah kata sandi" 
                   class="w-full px-4 py-2.5 bg-white border @error('password') border-rose-300 ring-2 ring-rose-100 @else border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
            <p class="text-[11px] text-slate-400">Minimal 8 karakter jika Anda ingin memperbarui kata sandi.</p>
            @error('password')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('users.index') }}" class="text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Batal
            </a>

            <button type="submit" id="btnSubmitUser" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition-all shadow-sm shadow-blue-500/20 cursor-pointer">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span id="btnSubmitUserText">Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

<script>
    let isUserSubmitting = false;
    document.getElementById('userForm').addEventListener('submit', function(e) {
        if (isUserSubmitting) {
            e.preventDefault();
            return false;
        }
        isUserSubmitting = true;
        const btn = document.getElementById('btnSubmitUser');
        const text = document.getElementById('btnSubmitUserText');
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        text.textContent = 'Menyimpan Perubahan...';
    });
</script>
@endsection