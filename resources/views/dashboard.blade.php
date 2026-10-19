@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="mb-2">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-500 text-sm mt-1">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Berikut adalah
                ringkasan aktivitas memo Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Belum Dibaca</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $unreadCount }}</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <i data-lucide="inbox" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Total Inbox Divisi</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $inboxCount }}</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                    <i data-lucide="send" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Memo Terkirim</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $sentCount }}</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-gray-100 text-gray-600 rounded-lg">
                    <i data-lucide="file-edit" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Draft Tersimpan</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $draftCount }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Memo Masuk Terbaru</h3>
                <a href="{{ route('inbox.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <table class="w-full text-left text-sm text-gray-600">
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentInboxes as $inbox)
                        @if ($inbox->memo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $inbox->memo->memo_number ?? 'DRAFT' }}</div>
                                    <div class="text-xs text-gray-500">{{ $inbox->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $inbox->memo->originDepartment->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $inbox->memo->subject ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('inbox.show', $inbox->id) }}"
                                        class="text-blue-600 hover:underline">Buka</a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada aktivitas memo terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endsection
