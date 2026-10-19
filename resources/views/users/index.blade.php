@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    Manajemen Akun
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500">{{ $users->count() }} Pengguna Terdaftar</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Pengguna</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar akun staf, manajer, dan administrator sistem E-Memo.</p>
        </div>

        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-xs sm:text-sm font-semibold transition-all shadow-sm shadow-blue-500/20">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nama & Email</th>
                        <th scope="col" class="px-6 py-4">Divisi Kerja</th>
                        <th scope="col" class="px-6 py-4">Hak Akses (Role)</th>
                        <th scope="col" class="px-6 py-4">Status Akun</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- User Name & Email with Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-400 truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Department -->
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $user->department->name ?? 'Belum Ditentukan' }}</span>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="px-6 py-4">
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    Super Admin
                                </span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    Admin
                                </span>
                            @elseif($user->role === 'manager')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Manager
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Staff
                                </span>
                            @endif
                        </td>

                        <!-- Status Active/Inactive -->
                        <td class="px-6 py-4">
                            @if($user->is_active)
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
                                <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Ubah Profil Pengguna" aria-label="Edit Pengguna">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" onsubmit="return confirmToggleStatus(event, this, 'Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun pengguna ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 {{ $user->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-xl transition-colors cursor-pointer" title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}" aria-label="Toggle Status">
                                            <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="max-w-sm mx-auto flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                    <i data-lucide="users" class="w-7 h-7"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">Belum Ada Pengguna</h3>
                                <p class="text-xs text-slate-400 mt-1">Data pengguna belum tersedia di sistem.</p>
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