@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Buat Memo Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Isi poin inti memo, format resmi akan disusun secara otomatis oleh sistem.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('memos.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @csrf
        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Divisi Asal (Pengirim)</label>
                    <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-600 text-sm">
                        {{ auth()->user()->department->name ?? '-' }}
                    </div>
                </div>

                <div>
                    <label for="target_department_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Kepada Yth. <span class="text-red-500">*</span></label>
                    <select id="target_department_id" name="target_department_id" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <option value="">Pilih Divisi Tujuan</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('target_department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1.5">Perihal <span class="text-red-500">*</span></label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="Contoh: Permintaan Pembelian HSF CPU Cooler" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1.5">Prioritas</label>
                    <select id="priority" name="priority" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <option value="normal">Normal</option>
                        <option value="high">Penting</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-1.5">Detail Pengajuan / Isi Memo <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="4" required placeholder="Tuliskan poin intinya saja. Salam pembuka dan penutup akan ditambahkan otomatis." class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">{{ old('content') }}</textarea>
            </div>

            <div>
                <label for="attachments" class="block text-sm font-semibold text-gray-700 mb-1.5">Lampiran (Opsional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50">
                    <div class="space-y-1 text-center">
                        <i data-lucide="upload-cloud" class="mx-auto h-10 w-10 text-gray-400"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Pilih file</span>
                                <input id="attachments" name="attachments[]" type="file" multiple class="sr-only" accept="image/*,.pdf,.doc,.docx">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
            <button type="submit" name="action_type" value="draft" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Draft
            </button>
            <button type="submit" name="action_type" value="send" class="px-4 py-2 bg-blue-600 border border-transparent text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i> Terbitkan Memo
            </button>
        </div>
    </form>
</div>
@endsection