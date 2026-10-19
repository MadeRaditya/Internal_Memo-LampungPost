@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <i data-lucide="inbox" class="w-3.5 h-3.5"></i>
                    Kotak Masuk
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500">{{ $inboxes->count() }} Total Memo</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kotak Masuk Memo</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar memo resmi yang ditujukan kepada divisi <strong class="text-slate-700">{{ auth()->user()->department->name }}</strong>.</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">No. Memo & Tanggal</th>
                        <th scope="col" class="px-6 py-4">Divisi Pengirim</th>
                        <th scope="col" class="px-6 py-4">Perihal</th>
                        <th scope="col" class="px-6 py-4">Status Memo</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inboxes as $inbox)
                    <tr class="hover:bg-slate-50/80 transition-colors {{ is_null($inbox->read_at) ? 'bg-blue-50/35 font-medium' : '' }}">
                        <!-- Memo Number & Date -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                @if(is_null($inbox->read_at))
                                    <span class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0" title="Belum dibuka"></span>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900">{{ $inbox->memo->memo_number ?? 'DRAFT' }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $inbox->created_at->translatedFormat('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Sender Department -->
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $inbox->memo->originDepartment->name ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Subject -->
                        <td class="px-6 py-4">
                            <div class="text-slate-800 line-clamp-2 max-w-sm">{{ $inbox->memo->subject }}</div>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4">
                            @if($inbox->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Belum Dibaca
                                </span>
                            @elseif($inbox->status === 'read')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Dibaca
                                </span>
                            @elseif($inbox->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            @elseif($inbox->status === 'forwarded')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Diteruskan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ strtoupper($inbox->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('inbox.show', $inbox->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200/60 transition-colors shadow-xs">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Buka Memo</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="max-w-sm mx-auto flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                    <i data-lucide="inbox" class="w-7 h-7"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">Kotak Masuk Kosong</h3>
                                <p class="text-xs text-slate-400 mt-1">Belum ada memo internal yang ditujukan ke divisi Anda saat ini.</p>
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