@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kotak Masuk (Inbox)</h2>
        <p class="text-gray-500 text-sm mt-1">Daftar memo yang ditujukan ke divisi {{ auth()->user()->department->name }}.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">No. Memo / Tgl</th>
                    <th class="px-6 py-4 font-semibold">Pengirim</th>
                    <th class="px-6 py-4 font-semibold">Perihal</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($inboxes as $inbox)
                <tr class="hover:bg-gray-50 transition-colors {{ is_null($inbox->read_at) ? 'bg-blue-50/30 font-medium text-gray-900' : '' }}">
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $inbox->memo->memo_number }}</div>
                        <div class="text-xs text-gray-500">{{ $inbox->created_at->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $inbox->memo->originDepartment->name }}</td>
                    <td class="px-6 py-4">{{ $inbox->memo->subject }}</td>
                    <td class="px-6 py-4">
                        @if($inbox->status === 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium uppercase">Belum Dibaca</span>
                        @elseif($inbox->status === 'read')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium uppercase">Dibaca</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium uppercase">{{ $inbox->status }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('inbox.show', $inbox->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs font-medium">
                            <i data-lucide="inbox" class="w-3.5 h-3.5"></i> Buka
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada memo yang masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection