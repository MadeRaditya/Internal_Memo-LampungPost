@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kelola Divisi</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar departemen atau divisi di lingkungan Lampung Post.</p>
        </div>
        <a href="{{ route('departments.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
            <i data-lucide="building" class="w-4 h-4"></i> Tambah Divisi
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">Kode</th>
                    <th class="px-6 py-4 font-semibold">Nama Divisi</th>
                    <th class="px-6 py-4 font-semibold text-center">Jml Anggota</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($departments as $dept)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $dept->code }}</td>
                    <td class="px-6 py-4">{{ $dept->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">{{ $dept->users_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($dept->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('departments.edit', $dept->id) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Divisi">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('departments.toggle-status', $dept->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status divisi ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ $dept->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i data-lucide="{{ $dept->is_active ? 'x-circle' : 'check-circle' }}" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection