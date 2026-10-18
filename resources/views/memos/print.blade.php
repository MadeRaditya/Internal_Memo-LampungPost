<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Memo - {{ $memo->memo_number }}</title>
    <style>
        @page { 
            size: A4; 
            margin: 0;
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 11pt; 
            color: #000; 
            line-height: 1.5; 
            margin: 0; 
            padding: 2.5cm; 
            box-sizing: border-box;
        }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .header-title { font-size: 12pt; margin-bottom: 5px; }
        .memo-number { font-size: 11pt; margin-bottom: 30px; }
        
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .meta-table td { padding: 4px 0; vertical-align: top; }
        .meta-table td.label { width: 120px; font-weight: bold; }
        .meta-table td.colon { width: 20px; text-align: center; font-weight: bold; }
        
        .divider { border-bottom: 2px solid #000; margin-bottom: 30px; }
        
        .content-body { text-align: justify; margin-bottom: 40px; min-height: 250px; }
        .content-body p { margin-bottom: 12px; }
        
        .signature-section { margin-top: 40px; }
        .qr-code { margin: 15px 0; }
        
        .page-break { page-break-before: always; padding-top: 2.5cm; }
        .attachment-container { margin-top: 20px; text-align: center; }
        .attachment-img { max-width: 100%; max-height: 800px; border: 1px solid #ddd; padding: 5px; margin-bottom: 10px; }
        .attachment-caption { font-size: 10pt; font-style: italic; color: #333; }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center">
        <div class="header-title font-bold">MEMO INTERNAL</div>
        <div class="memo-number font-bold">{{ $memo->memo_number ?? 'Nm. DRAFT' }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Kepada Yth</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $recipient->targetDepartment->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $memo->originDepartment->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $memo->subject }}</td>
        </tr>
        <tr>
            <td class="label">Lampiran</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $memo->attachments->count() > 0 ? $memo->attachments->count() . ' File/Halaman' : '-' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="content-body">
        <p>Dengan Hormat,</p>
        <p>Bersama surat ini kami mengajukan permintaan untuk {{ $memo->content }}. Contoh terlampir.</p>
        <p>Demikian pengajuan ini kami buat, atas kerjasamanya kami ucapkan terima kasih.</p>
    </div>

    <div class="signature-section">
        <div class="font-bold">Dibuat Oleh,</div>
        
        <div class="qr-code">
            {!! QrCode::size(100)->generate(
                "Sudah di tanda tangani oleh :\n\n" .
                "Nama : " . ($memo->sender->name ?? '') . "\n" .
                "Bagian : " . ($memo->originDepartment->code ?? '') . "\n" .
                "Jabatan : " . strtoupper($memo->sender->role ?? '') . "\n\n" .
                "Perihal : " . $memo->subject . "\n" .
                "Tgl : " . ($memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-')
            ) !!}
        </div>

        <div class="font-bold" style="text-decoration: underline;">{{ $memo->sender->name ?? '' }}</div>
        <div>{{ $memo->originDepartment->code ?? '' }}</div>
    </div>

    @if($memo->attachments->count() > 0)
        @foreach($memo->attachments as $index => $attachment)
            @if(str_starts_with($attachment->file_mime, 'image/'))
                <div class="page-break">
                    <h3 class="text-center font-bold" style="margin-bottom: 20px;">LAMPIRAN {{ $index + 1 }}</h3>
                    <div class="attachment-container">
                        <img src="{{ asset('storage/' . $attachment->file_path) }}" class="attachment-img" alt="Lampiran {{ $index + 1 }}">
                        <div class="attachment-caption">Gambar Lampiran: {{ $attachment->original_name }}</div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

</body>
</html>