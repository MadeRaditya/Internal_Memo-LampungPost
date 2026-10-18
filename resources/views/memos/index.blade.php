@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Memo Saya</h2>
            <p class="text-gray-500 text-sm mt-1">Riwayat memo yang Anda buat dan kirim.</p>
        </div>
        <a href="{{ route('memos.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Buat Memo
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">No. Memo / Tgl</th>
                    <th class="px-6 py-4 font-semibold">Perihal</th>
                    <th class="px-6 py-4 font-semibold">Tujuan</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($memos as $m)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $m->memo_number ?? 'DRAFT' }}</div>
                        <div class="text-xs text-gray-500">{{ $m->created_at->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $m->subject }}</td>
                    <td class="px-6 py-4">{{ $m->recipients->first()->targetDepartment->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($m->status === 'draft')
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium uppercase">Draft</span>
                        @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium uppercase">{{ $m->status }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('memos.show', $m->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs font-medium">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada memo yang dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection