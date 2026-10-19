<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Memo Resmi - {{ $memo->memo_number ?? 'DRAFT' }}</title>
    <style>
        @page { 
            size: A4 portrait; 
            margin: 2cm 2.5cm;
        }
        
        * {
            box-sizing: border-box;
        }

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            color: #000; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0;
            background: #fff;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header-title { 
            font-size: 15pt; 
            letter-spacing: 1px;
            margin-bottom: 2px; 
            text-transform: uppercase;
        }
        
        .memo-number { 
            font-size: 11pt; 
            font-family: Arial, sans-serif;
            margin-bottom: 25px; 
        }
        
        .meta-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
            font-size: 11pt;
        }
        
        .meta-table td { 
            padding: 3px 0; 
            vertical-align: top; 
        }
        
        .meta-table td.label { 
            width: 130px; 
            font-weight: bold; 
        }
        
        .meta-table td.colon { 
            width: 20px; 
            text-align: center; 
            font-weight: bold; 
        }
        
        .divider { 
            border-bottom: 2px solid #000; 
            margin-bottom: 25px; 
        }
        
        .content-body { 
            text-align: justify; 
            margin-bottom: 35px; 
            min-height: 180px; 
            font-size: 12pt;
        }
        
        .content-body p { 
            margin-bottom: 14px; 
            text-indent: 0;
        }
        
        .signature-section { 
            margin-top: 30px; 
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            width: 260px;
            text-align: left;
        }
        
        .qr-code { 
            margin: 10px 0; 
        }
        
        .page-break { 
            page-break-before: always; 
            padding-top: 1cm; 
        }

        .attachment-container { 
            margin-top: 20px; 
            text-align: center; 
        }

        .attachment-img { 
            max-width: 90%; 
            max-height: 750px; 
            border: 1px solid #999; 
            padding: 4px; 
            margin-bottom: 10px; 
        }

        .attachment-caption { 
            font-size: 10pt; 
            font-style: italic; 
            color: #333; 
        }

        /* Non-print toolbar for quick preview control */
        @media screen {
            body {
                background: #e2e8f0;
                padding: 30px 15px;
            }
            .print-page {
                background: #fff;
                width: 210mm;
                min-height: 297mm;
                margin: 0 auto;
                padding: 2.5cm;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            }
            .screen-toolbar {
                width: 210mm;
                margin: 0 auto 20px auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-family: Arial, sans-serif;
            }
            .btn-print {
                background: #1e293b;
                color: #fff;
                padding: 8px 18px;
                border-radius: 6px;
                text-decoration: none;
                font-size: 13px;
                font-weight: bold;
                border: none;
                cursor: pointer;
            }
        }

        @media print {
            .screen-toolbar { display: none; }
            .print-page { padding: 0; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="screen-toolbar">
        <div>
            <strong>Pratinjau Cetak Lembar Memo Resmi</strong>
        </div>
        <button onclick="window.print()" class="btn-print">Cetak Dokumen (Ctrl+P)</button>
    </div>

    <div class="print-page">
        <!-- Title & Memo Number -->
        <div class="text-center">
            <div class="header-title font-bold">MEMO INTERNAL</div>
            <div class="memo-number font-bold">{{ $memo->memo_number ?? 'Nm. DRAFT' }}</div>
        </div>

        <!-- Meta Table -->
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
                <td class="font-bold">{{ $memo->attachments->count() > 0 ? $memo->attachments->count() . ' Berkas' : '-' }}</td>
            </tr>
        </table>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Content -->
        <div class="content-body">
            <p>Dengan Hormat,</p>
            <p>Bersama surat ini kami mengajukan permohonan untuk <strong>{{ $memo->content }}</strong>.</p>
            <p>Demikian pengajuan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
        </div>

        <!-- Digital Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="font-bold">Dibuat Oleh,</div>
                <div class="qr-code">
                    {!! QrCode::size(95)->generate(
                        "Sudah ditandatangani secara digital oleh:\n\n" .
                        "Nama : " . ($memo->sender->name ?? '') . "\n" .
                        "Bagian : " . ($memo->originDepartment->code ?? '') . "\n" .
                        "Jabatan : " . strtoupper($memo->sender->role ?? '') . "\n\n" .
                        "Perihal : " . $memo->subject . "\n" .
                        "Tgl : " . ($memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-')
                    ) !!}
                </div>
                <div class="font-bold" style="text-decoration: underline;">{{ $memo->sender->name ?? '' }}</div>
                <div style="font-size: 10pt; text-transform: uppercase;">{{ $memo->originDepartment->name ?? '' }} ({{ $memo->originDepartment->code ?? '' }})</div>
            </div>
        </div>

        <!-- Attachment Images on Separate Pages -->
        @if($memo->attachments->count() > 0)
            @foreach($memo->attachments as $index => $attachment)
                @if(str_starts_with($attachment->file_mime, 'image/'))
                    <div class="page-break">
                        <div class="text-center font-bold" style="font-size: 13pt; margin-bottom: 20px;">
                            LAMPIRAN {{ $index + 1 }}
                        </div>
                        <div class="attachment-container">
                            <img src="{{ asset('storage/' . $attachment->file_path) }}" class="attachment-img" alt="Lampiran {{ $index + 1 }}">
                            <div class="attachment-caption">{{ $attachment->original_name }}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

</body>
</html>