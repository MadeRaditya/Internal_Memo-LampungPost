@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6 pb-12">

    <!-- Header & Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('departments.index') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors" title="Kembali ke Daftar Divisi" aria-label="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Divisi Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftarkan divisi kerja baru ke dalam struktur perusahaan.</p>
            </div>
        </div>
    </div>

    <!-- Creation Form Card -->
    <form id="departmentForm" method="POST" action="{{ route('departments.store') }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8 space-y-6">
        @csrf

        <div class="space-y-4">
            <div>
                <label for="code" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                    Kode Singkatan Divisi <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" required 
                       placeholder="Contoh: RED, KEU, IT, UMUM" 
                       oninput="this.value = this.value.toUpperCase()"
                       class="w-full px-4 py-2.5 bg-slate-50 border @error('code') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm font-mono font-bold text-slate-900 uppercase outline-none transition-all">
                <p class="text-[11px] text-slate-400 mt-1">Kode digunakan sebagai bagian dari penomoran surat resmi dan otomatis diubah menjadi kapital.</p>
                @error('code')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                    Nama Lengkap Divisi <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="Contoh: Redaksi & Pemberitaan" 
                       class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('departments.index') }}" class="text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Batal
            </a>

            <button type="submit" id="btnSubmitDept" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition-all shadow-sm shadow-blue-500/20 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span id="btnSubmitDeptText">Simpan Divisi</span>
            </button>
        </div>
    </form>
</div>

<script>
    let isDeptSubmitting = false;
    document.getElementById('departmentForm').addEventListener('submit', function(e) {
        if (isDeptSubmitting) {
            e.preventDefault();
            return false;
        }
        isDeptSubmitting = true;
        const btn = document.getElementById('btnSubmitDept');
        const text = document.getElementById('btnSubmitDeptText');
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        text.textContent = 'Menyimpan...';
    });
</script>
@endsection