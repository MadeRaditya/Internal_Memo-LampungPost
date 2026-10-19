@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-10">
    <div class="flex items-center justify-between bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Memo Masuk</h2>
            <p class="text-sm text-gray-500 mt-1">No: {{ $memo->memo_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inbox.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Kembali
            </a>
            <a href="{{ route('memos.print', $memo->id) }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition-colors flex items-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Dokumen
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-4">
            <div><span class="text-gray-500 block mb-1">Pengirim:</span> <span class="font-medium text-gray-900">{{ $memo->originDepartment->name }} ({{ $memo->sender->name }})</span></div>
            <div><span class="text-gray-500 block mb-1">Tanggal Dikirim:</span> <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($memo->published_date)->format('d M Y') }}</span></div>
            <div><span class="text-gray-500 block mb-1">Perihal:</span> <span class="font-medium text-gray-900">{{ $memo->subject }}</span></div>
            <div><span class="text-gray-500 block mb-1">Prioritas:</span> <span class="font-medium text-gray-900 uppercase">{{ $memo->priority }}</span></div>
        </div>
        <div>
            <span class="text-gray-500 block mb-1 text-sm">Isi Inti Pengajuan:</span>
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800 text-sm">
                {{ $memo->content }}
            </div>
        </div>
    </div>

    @if(in_array($recipient->status, ['pending', 'read']))
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Tindak Lanjut Memo</h3>
        <form action="{{ route('inbox.process', $recipient->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Aksi Tindak Lanjut</label>
                <select name="action_type" id="action_type" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all" onchange="toggleForward(this.value)">
                    <option value="">Pilih Tindakan...</option>
                    <option value="complete">Selesaikan Memo (Telah diproses/disetujui)</option>
                    <option value="forward">Teruskan (Forward) ke Divisi Lain</option>
                </select>
            </div>

            <div id="forward_div" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Teruskan Kepada Yth.</label>
                <select name="forward_department_id" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <option value="">Pilih Divisi...</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan / Respons</label>
                <textarea name="response_notes" rows="3" placeholder="Tambahkan catatan proses atau alasan penerusan..." class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
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
    @else
    <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center gap-3">
        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
        <div>
            <h4 class="font-semibold text-green-800">Memo telah diproses</h4>
            <p class="text-sm text-green-700">Status: {{ strtoupper($recipient->status) }} pada {{ $recipient->responded_at->format('d M Y H:i') }}</p>
            @if($recipient->response_notes)
                <p class="text-sm text-green-700 mt-1 font-medium">Catatan: {{ $recipient->response_notes }}</p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection