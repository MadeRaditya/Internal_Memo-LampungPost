@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-10">
    <div class="flex items-center justify-between bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Pemrosesan Memo Masuk</h2>
            <p class="text-sm text-gray-500 mt-1">No: {{ $memo->memo_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inbox.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Kembali
            </a>
            <a href="{{ route('memos.print', $memo->id) }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition-colors flex items-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </a>
        </div>
    </div>

    <div class="bg-white mx-auto shadow-lg border border-gray-200 relative" style="width: 210mm; padding: 2.5cm; font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #000;">
        <div class="text-center" style="margin-bottom: 30px;">
            <div style="font-size: 12pt; font-weight: bold; margin-bottom: 5px;">MEMO INTERNAL</div>
            <div style="font-size: 11pt; font-weight: bold;">{{ $memo->memo_number }}</div>
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
                <td style="font-weight: bold; padding: 4px 0; vertical-align: top;">{{ $memo->attachments->count() > 0 ? $memo->attachments->count() . ' File' : '-' }}</td>
            </tr>
        </table>

        <div style="border-bottom: 2px solid #000; margin-bottom: 30px;"></div>

        <div style="text-align: justify; margin-bottom: 40px; min-height: 150px;">
            <p style="margin-bottom: 12px;">Dengan Hormat,</p>
            <p style="margin-bottom: 12px;">Bersama surat ini kami mengajukan permintaan untuk {{ $memo->content }}. Contoh terlampir.</p>
            <p style="margin-bottom: 12px;">Demikian pengajuan ini kami buat, atas kerjasamanya kami ucapkan terima kasih.</p>
        </div>

        <div style="margin-top: 20px;">
            <div style="font-weight: bold;">Dibuat Oleh,</div>
            <div style="margin: 15px 0;">
                {!! QrCode::size(100)->generate("Sudah di tanda tangani oleh :\nNama : " . ($memo->sender->name ?? '') . "\nBagian : " . ($memo->originDepartment->code ?? '') . "\nTgl : " . ($memo->published_date ? \Carbon\Carbon::parse($memo->published_date)->isoFormat('D MMMM Y') : '-')) !!}
            </div>
            <div style="font-weight: bold; text-decoration: underline;">{{ $memo->sender->name ?? '' }}</div>
            <div>{{ $memo->originDepartment->code ?? '' }}</div>
        </div>
    </div>

    @if(in_array($recipient->status, ['pending', 'read']))
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Tindak Lanjut Memo</h3>
        <form action="{{ route('inbox.process', $recipient->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Aksi Tindak Lanjut</label>
                <select name="action_type" id="action_type" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" onchange="toggleForward(this.value)">
                    <option value="">Pilih Tindakan...</option>
                    <option value="complete">Selesaikan Memo (Telah diproses/disetujui)</option>
                    <option value="forward">Teruskan (Forward) ke Divisi Lain</option>
                </select>
            </div>

            <div id="forward_div" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Teruskan Kepada Yth.</label>
                <select name="forward_department_id" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Divisi...</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan / Respons</label>
                <textarea name="response_notes" rows="3" placeholder="Tambahkan catatan proses atau alasan penerusan..." class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Proses Memo
                </button>
            </div>
        </form>
    </div>
    <script>
        function toggleForward(val) {
            const fwdDiv = document.getElementById('forward_div');
            if(val === 'forward') {
                fwdDiv.classList.remove('hidden');
                fwdDiv.querySelector('select').required = true;
            } else {
                fwdDiv.classList.add('hidden');
                fwdDiv.querySelector('select').required = false;
            }
        }
    </script>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Riwayat Perjalanan Memo</h3>
        <div class="space-y-4">
            @forelse($memo->histories as $history)
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    @if(!$loop->last)
                    <div class="w-0.5 h-full bg-gray-200 my-1"></div>
                    @endif
                </div>
                <div class="pb-4">
                    <div class="text-sm font-semibold text-gray-800">{{ $history->action }}</div>
                    <div class="text-sm text-gray-600">{{ $history->description }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $history->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
            @empty
            <div class="text-sm text-gray-500">Belum ada riwayat.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection