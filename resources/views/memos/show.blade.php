@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-10">
    <div class="flex items-center justify-between bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Preview Memo</h2>
            <p class="text-sm text-gray-500 mt-1">Status: <span class="uppercase font-semibold text-blue-600">{{ $memo->status }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('memos.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Kembali
            </a>

            @if($memo->status === 'draft' && $memo->sender_user_id === auth()->id())
                <a href="{{ route('memos.edit', $memo->id) }}" class="px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm font-medium rounded-lg hover:bg-yellow-100 transition-colors flex items-center gap-1.5">
                    <i data-lucide="edit" class="w-4 h-4"></i> Edit Draft
                </a>
                <form action="{{ route('memos.destroy', $memo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draft ini permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors flex items-center gap-1.5">
                        <i data-lucide="trash" class="w-4 h-4"></i> Hapus
                    </button>
                </form>
            @endif

            <a href="{{ route('memos.print', $memo->id) }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition-colors flex items-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Dokumen
            </a>
        </div>
    </div>

    <div class="bg-white mx-auto shadow-lg border border-gray-200 relative" style="width: 210mm; min-height: 297mm; padding: 2.5cm; font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #000;">
        
        <div class="text-center" style="margin-bottom: 30px;">
            <div style="font-size: 12pt; font-weight: bold; margin-bottom: 5px;">MEMO INTERNAL</div>
            <div style="font-size: 11pt; font-weight: bold;">{{ $memo->memo_number ?? 'Nm. DRAFT' }}</div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 120px; font-weight: bold; padding: 4px 0; vertical-align: top;">Kepada Yth</td>
                <td style="width: 20px; text-align: center; font-weight: bold; padding: 4px 0; vertical-align: top;">:</td>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">{{ $recipient->targetDepartment->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">Dari</td>
                <td style="text-align: center; font-weight: bold; padding: 4px 0; vertical-align: top;">:</td>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">{{ $memo->originDepartment->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">Tanggal</td>
                <td style="text-align: center; font-weight: bold; padding: 4px 0; vertical-align: top;">:</td>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">{{ $memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">Perihal</td>
                <td style="text-align: center; font-weight: bold; padding: 4px 0; vertical-align: top;">:</td>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">{{ $memo->subject }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">Lampiran</td>
                <td style="text-align: center; font-weight: bold; padding: 4px 0; vertical-align: top;">:</td>
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">{{ $memo->attachments->count() > 0 ? $memo->attachments->count() . ' File/Halaman' : '-' }}</td>
            </tr>
        </table>

        <div style="border-bottom: 2px solid #000; margin-bottom: 30px;"></div>

        <div style="text-align: justify; margin-bottom: 40px; min-height: 250px;">
            <p style="margin-bottom: 12px;">Dengan Hormat,</p>
            <p style="margin-bottom: 12px;">Bersama surat ini kami mengajukan permintaan untuk {{ $memo->content }}. Contoh terlampir.</p>
            <p style="margin-bottom: 12px;">Demikian pengajuan ini kami buat, atas kerjasamanya kami ucapkan terima kasih.</p>
        </div>

        <div style="margin-top: 40px;">
            <div style="font-weight: bold;">Dibuat Oleh,</div>
            <div style="margin: 15px 0;">
                {!! QrCode::size(100)->generate(
                    "Sudah di tanda tangani oleh :\n\n" .
                    "Nama : " . ($memo->sender->name ?? '') . "\n" .
                    "Bagian : " . ($memo->originDepartment->code ?? '') . "\n" .
                    "Jabatan : " . strtoupper($memo->sender->role ?? '') . "\n\n" .
                    "Perihal : " . $memo->subject . "\n" .
                    "Tgl : " . ($memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-')
                ) !!}
            </div>
            <div style="font-weight: bold; text-decoration: underline;">{{ $memo->sender->name ?? '' }}</div>
            <div>{{ $memo->originDepartment->code ?? '' }}</div>
        </div>

        @if($memo->attachments->count() > 0)
            @foreach($memo->attachments as $index => $attachment)
                @if(str_starts_with($attachment->file_mime, 'image/'))
                    <div style="page-break-before: always; padding-top: 50px;">
                        <h3 style="text-align: center; font-weight: bold; margin-bottom: 20px;">LAMPIRAN {{ $index + 1 }}</h3>
                        <div style="text-align: center; margin-top: 20px;">
                            <img src="{{ asset('storage/' . $attachment->file_path) }}" style="max-width: 100%; max-height: 700px; border: 1px solid #ddd; padding: 5px; margin-bottom: 10px;" alt="Lampiran {{ $index + 1 }}">
                            <!-- Caption dibikin standar -->
                            <div style="font-size: 10pt; font-style: italic; color: #333;">Dokumen Lampiran {{ $index + 1 }}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

    </div>
</div>
@endsection