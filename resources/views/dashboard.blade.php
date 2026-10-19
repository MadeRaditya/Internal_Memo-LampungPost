@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Welcome Greeting & Quick Info Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    {{ auth()->user()->department->name ?? 'Divisi Umum' }}
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Berikut adalah ikhtisar aktivitas peredaran memo internal divisi Anda hari ini.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('memos.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-xs sm:text-sm font-semibold transition-all shadow-sm shadow-blue-500/20">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Tulis Memo Baru</span>
            </a>
        </div>
    </div>

    <!-- 4 Primary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Unread Memos (Needs Attention) -->
        <a href="{{ route('inbox.index') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-amber-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perlu Dibaca</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $unreadCount }}</div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                    <span class="{{ $unreadCount > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                        {{ $unreadCount > 0 ? 'Memo belum dibuka' : 'Semua telah dibaca' }}
                    </span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Total Inbox -->
        <a href="{{ route('inbox.index') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-blue-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kotak Masuk</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="inbox" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $inboxCount }}</div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                    <span>Total memo diterima</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Sent Memos -->
        <a href="{{ route('memos.index') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Memo Keluar</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $sentCount }}</div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                    <span>Memo resmi terbit</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Draft Memos -->
        <a href="{{ route('memos.index') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Draft Disimpan</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="file-edit" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $draftCount }}</div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                    <span>Konsep belum terbit</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Inbox Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Memo Masuk Terbaru</h2>
                    <p class="text-xs text-slate-500">5 memo terakhir yang ditujukan ke divisi Anda</p>
                </div>
            </div>
            <a href="{{ route('inbox.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                <span>Buka Semua Kotak Masuk</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">No. Memo & Tanggal</th>
                        <th scope="col" class="px-6 py-3.5">Divisi Pengirim</th>
                        <th scope="col" class="px-6 py-3.5">Perihal</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentInboxes as $inbox)
                        @if ($inbox->memo)
                            <tr class="hover:bg-slate-50/70 transition-colors {{ is_null($inbox->read_at) ? 'bg-blue-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if(is_null($inbox->read_at))
                                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0" title="Belum dibaca"></span>
                                        @endif
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $inbox->memo->memo_number ?? 'DRAFT' }}</div>
                                            <div class="text-xs text-slate-400 mt-0.5">{{ $inbox->created_at->translatedFormat('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                        <i data-lucide="building" class="w-3 h-3 text-slate-400"></i>
                                        {{ $inbox->memo->originDepartment->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800 line-clamp-1 max-w-md">{{ $inbox->memo->subject ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('inbox.show', $inbox->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                        <span>Buka Memo</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="max-w-xs mx-auto flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Belum ada memo masuk</p>
                                    <p class="text-xs text-slate-400 mt-1">Saat ini divisi Anda belum menerima memo internal baru dari divisi lain.</p>
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
