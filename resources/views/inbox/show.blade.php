@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Top Action Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('inbox.index') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors" title="Kembali ke Kotak Masuk" aria-label="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-lg font-bold text-slate-900">Pemrosesan Memo Masuk</h1>
                    @if($recipient->status === 'pending')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Belum Ditindaklanjuti
                        </span>
                    @elseif($recipient->status === 'read')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Telah Dibaca
                        </span>
                    @elseif($recipient->status === 'completed')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai Diproses
                        </span>
                    @elseif($recipient->status === 'forwarded')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Diteruskan
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5 font-mono">Nomor: {{ $memo->memo_number ?? 'DRAFT' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
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
            <div class="text-justify mb-10 min-h-[140px] text-sm sm:text-base leading-relaxed space-y-4">
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
                        {!! QrCode::size(95)->generate("Sudah ditandatangani secara digital oleh:\nNama: " . ($memo->sender->name ?? '') . "\nBagian: " . ($memo->originDepartment->code ?? '') . "\nTgl: " . ($memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-')) !!}
                    </div>
                    <p class="font-bold text-sm underline">{{ $memo->sender->name ?? '' }}</p>
                    <p class="text-xs text-slate-600 uppercase">{{ $memo->originDepartment->name ?? '' }} ({{ $memo->originDepartment->code ?? '' }})</p>
                </div>
            </div>

            <!-- Attached Files Preview inside Viewer -->
            @if($memo->attachments->count() > 0)
                <div class="mt-12 pt-8 border-t border-slate-200">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-4">Daftar Berkas Lampiran</h3>
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

    <!-- Follow Up / Processing Action Form -->
    @if(in_array($recipient->status, ['pending', 'read']))
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="check-square" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Tindak Lanjut Memo Masuk</h3>
                <p class="text-xs text-slate-500">Tentukan respons atau teruskan memo ini ke divisi yang berwenang</p>
            </div>
        </div>

        <form id="processMemoForm" action="{{ route('inbox.process', $recipient->id) }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="action_type" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                    Aksi Keputusan <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <select name="action_type" id="action_type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-xl text-sm text-slate-900 outline-none transition-all" onchange="toggleForward(this.value)">
                    <option value="">-- Pilih Tindakan Memo --</option>
                    <option value="complete">Selesaikan Memo (Telah diproses / disetujui divisi ini)</option>
                    <option value="forward">Teruskan (Forward) ke Divisi Terkait Lainnya</option>
                </select>
            </div>

            <div id="forward_div" class="hidden space-y-1.5">
                <label for="forward_department_id" class="block text-xs font-bold text-slate-700 tracking-wide uppercase">
                    Teruskan Kepada Divisi <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <select name="forward_department_id" id="forward_department_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-xl text-sm text-slate-900 outline-none transition-all">
                    <option value="">-- Pilih Divisi Tujuan Penerusan --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="response_notes" class="block text-xs font-bold text-slate-700 tracking-wide uppercase">
                    Catatan Disposisi / Keterangan
                </label>
                <textarea id="response_notes" name="response_notes" rows="3" placeholder="Tuliskan catatan proses, rekomendasi, atau alasan penerusan memo..." class="w-full px-4 py-3 bg-slate-50 border border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-xl text-sm text-slate-900 outline-none transition-all"></textarea>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" id="btnSubmitProcess" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition-all shadow-sm shadow-blue-500/20 cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span id="btnSubmitProcessText">Kirim Pemrosesan</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleForward(val) {
            const fwdDiv = document.getElementById('forward_div');
            const fwdSelect = document.getElementById('forward_department_id');
            if(val === 'forward') {
                fwdDiv.classList.remove('hidden');
                fwdSelect.required = true;
            } else {
                fwdDiv.classList.add('hidden');
                fwdSelect.required = false;
            }
        }

        // Prevent double submit on processing
        let isProcessSubmitting = false;
        document.getElementById('processMemoForm').addEventListener('submit', function(e) {
            if (isProcessSubmitting) {
                e.preventDefault();
                return false;
            }
            isProcessSubmitting = true;
            const btn = document.getElementById('btnSubmitProcess');
            const text = document.getElementById('btnSubmitProcessText');
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            text.textContent = 'Memproses...';
        });
    </script>
    @endif

    <!-- Memo History / Timeline Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-4 mb-6">
            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                <i data-lucide="history" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Riwayat Perjalanan & Disposisi Memo</h3>
                <p class="text-xs text-slate-400">Jejak audit seluruh aktivitas pemrosesan memo ini</p>
            </div>
        </div>

        <div class="space-y-6 relative before:absolute before:inset-0 before:left-4 before:w-0.5 before:bg-slate-100">
            @forelse($memo->histories as $history)
            <div class="relative flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-blue-50 border-2 border-white text-blue-600 flex items-center justify-center shadow-xs z-10 flex-shrink-0">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                </div>
                <div class="flex-1 bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ $history->action }}</span>
                        <span class="text-[11px] font-medium text-slate-400">{{ $history->created_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700">{{ $history->description }}</p>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic">Belum ada riwayat aktivitas tercatat.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection