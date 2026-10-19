@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Edit Divisi</h2>
        <a href="{{ route('departments.index') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
    </div>

    <form method="POST" action="{{ route('departments.update', $department->id) }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Divisi</label>
            <input type="text" name="code" value="{{ old('code', $department->code) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase">
            @error('code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Divisi Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $department->name) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection