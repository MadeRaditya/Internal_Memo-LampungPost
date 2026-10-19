@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    Struktur Organisasi
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500">{{ $departments->count() }} Total Divisi</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Divisi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar departemen dan divisi kerja di lingkungan perusahaan Lampung Post.</p>
        </div>

        <a href="{{ route('departments.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-xs sm:text-sm font-semibold transition-all shadow-sm shadow-blue-500/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Divisi</span>
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Kode Divisi</th>
                        <th scope="col" class="px-6 py-4">Nama Divisi Lengkap</th>
                        <th scope="col" class="px-6 py-4 text-center">Jumlah Personil</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($departments as $dept)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- Code -->
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-extrabold font-mono bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $dept->code }}
                            </span>
                        </td>

                        <!-- Name -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $dept->name }}</div>
                        </td>

                        <!-- Personnel Count -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                <i data-lucide="users" class="w-3 h-3 text-slate-400"></i>
                                <span>{{ $dept->users_count }} Staf</span>
                            </span>
                        </td>

                        <!-- Status Active/Inactive -->
                        <td class="px-6 py-4">
                            @if($dept->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('departments.edit', $dept->id) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Ubah Nama/Kode Divisi" aria-label="Edit Divisi">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>

                                <form action="{{ route('departments.toggle-status', $dept->id) }}" method="POST" onsubmit="return confirmToggleStatus(event, this, 'Apakah Anda yakin ingin {{ $dept->is_active ? 'menonaktifkan' : 'mengaktifkan kembali' }} divisi ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 {{ $dept->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-xl transition-colors cursor-pointer" title="{{ $dept->is_active ? 'Nonaktifkan Divisi' : 'Aktifkan Divisi' }}" aria-label="Toggle Status">
                                        <i data-lucide="{{ $dept->is_active ? 'pause-circle' : 'play-circle' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="max-w-sm mx-auto flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                    <i data-lucide="building-2" class="w-7 h-7"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">Belum Ada Divisi</h3>
                                <p class="text-xs text-slate-400 mt-1">Data struktur divisi belum ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection