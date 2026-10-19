@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Top Action & Navigation Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('memos.index') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors" title="Kembali ke Daftar Memo" aria-label="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-lg font-bold text-slate-900">Pratinjau Memo Keluar</h1>
                    @if($memo->status === 'draft')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                        </span>
                    @elseif($memo->status === 'in_progress')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Sedang Diproses
                        </span>
                    @elseif($memo->status === 'completed')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ strtoupper($memo->status) }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5 font-mono">No: {{ $memo->memo_number ?? 'KONSEP DRAFT' }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            @if($memo->status === 'draft' && $memo->sender_user_id === auth()->id())
                <a href="{{ route('memos.edit', $memo->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-colors">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Ubah Draft</span>
                </a>

                <form action="{{ route('memos.destroy', $memo->id) }}" method="POST" onsubmit="return confirmDelete(event, this, 'Hapus konsep draft memo ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <span>Hapus</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('memos.print', $memo->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-slate-800 hover:bg-slate-900 active:scale-[0.99] text-white transition-all shadow-xs">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Lembar Resmi</span>
            </a>
        </div>
    </div>

    <!-- Official Memo Document Viewer (Paper Representation) -->
    <div class="bg-slate-100/80 p-4 sm:p-8 rounded-2xl border border-slate-200/80 flex justify-center overflow-x-auto">
        <div class="bg-white w-full max-w-[210mm] shadow-xl border border-slate-200 rounded-sm p-6 sm:p-12 md:p-16 text-slate-900 relative" style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.6;">
            
            <!-- Document Title -->
            <div class="text-center pb-6 mb-6">
                <h2 class="text-xl sm:text-2xl font-bold tracking-wide uppercase mb-1">MEMO INTERNAL</h2>
                <p class="text-sm sm:text-base font-bold font-mono">{{ $memo->memo_number ?? 'Nm. DRAFT' }}</p>
            </div>

            <!-- Metadata Header -->
            <table class="w-full text-xs sm:text-sm mb-6 border-collapse">
                <tbody>
                    <tr>
                        <td class="w-28 sm:w-36 py-1.5 font-bold align-top">Kepada Yth</td>
                        <td class="w-5 py-1.5 font-bold text-center align-top">:</td>
                        <td class="py-1.5 font-bold text-slate-900 align-top">{{ $recipient->targetDepartment->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-bold align-top">Dari</td>
                        <td class="py-1.5 font-bold text-center align-top">:</td>
                        <td class="py-1.5 font-bold text-slate-900 align-top">{{ $memo->originDepartment->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-bold align-top">Tanggal</td>
                        <td class="py-1.5 font-bold text-center align-top">:</td>
                        <td class="py-1.5 font-bold text-slate-900 align-top">{{ $memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-bold align-top">Perihal</td>
                        <td class="py-1.5 font-bold text-center align-top">:</td>
                        <td class="py-1.5 font-bold text-slate-900 align-top">{{ $memo->subject }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-bold align-top">Lampiran</td>
                        <td class="py-1.5 font-bold text-center align-top">:</td>
                        <td class="py-1.5 font-bold text-slate-900 align-top">
                            {{ $memo->attachments->count() > 0 ? $memo->attachments->count() . ' Berkas' : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Divider Line -->
            <div class="border-b-2 border-black mb-8"></div>

            <!-- Memo Content Body -->
            <div class="text-justify mb-10 min-h-[160px] text-sm sm:text-base leading-relaxed space-y-4">
                <p>Dengan Hormat,</p>
                <p>Bersama surat ini kami mengajukan permohonan untuk <strong>{{ $memo->content }}</strong>.</p>
                <p>Demikian pengajuan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
            </div>

            <!-- Signature & QR Verification -->
            <div class="mt-8 pt-4 flex flex-col sm:flex-row justify-between items-end gap-6">
                <div></div>
                <div class="text-left w-64">
                    <p class="font-bold mb-3 text-sm">Dibuat Oleh,</p>
                    <div class="my-3 p-2 bg-white inline-block border border-slate-200 rounded-lg shadow-2xs">
                        {!! QrCode::size(95)->generate(
                            "Sudah ditandatangani secara digital oleh:\n\n" .
                            "Nama : " . ($memo->sender->name ?? '') . "\n" .
                            "Bagian : " . ($memo->originDepartment->code ?? '') . "\n" .
                            "Jabatan : " . strtoupper($memo->sender->role ?? '') . "\n\n" .
                            "Perihal : " . $memo->subject . "\n" .
                            "Tgl : " . ($memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-')
                        ) !!}
                    </div>
                    <p class="font-bold text-sm underline">{{ $memo->sender->name ?? '' }}</p>
                    <p class="text-xs text-slate-600 uppercase">{{ $memo->originDepartment->name ?? '' }} ({{ $memo->originDepartment->code ?? '' }})</p>
                </div>
            </div>

            <!-- Attached Files Inside Letter Viewer -->
            @if($memo->attachments->count() > 0)
                <div class="mt-12 pt-8 border-t border-slate-200">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-4">Berkas Lampiran Pendukung</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($memo->attachments as $index => $att)
                            <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="{{ str_starts_with($att->file_mime, 'image/') ? 'image' : 'file-text' }}" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $att->original_name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ number_format($att->file_size / 1024, 1) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-blue-600 bg-white border border-slate-200 hover:bg-blue-50 transition-colors flex-shrink-0">
                                    Lihat
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection