@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Buat Memo Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Isi poin inti memo, format resmi akan disusun secara otomatis oleh sistem.</p>
        </div>
    </div>

    <form id="memoForm" method="POST" action="{{ route('memos.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
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
                    <select id="target_department_id" name="target_department_id" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
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
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="Contoh: Permintaan Pembelian HSF CPU Cooler" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1.5">Prioritas</label>
                    <select id="priority" name="priority" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="normal">Normal</option>
                        <option value="high">Penting</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-1.5">Detail Pengajuan / Isi Memo <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="4" required placeholder="Tuliskan poin intinya saja. Salam pembuka dan penutup akan ditambahkan otomatis." class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">{{ old('content') }}</textarea>
            </div>

            <div>
                <label for="attachments" class="block text-sm font-semibold text-gray-700 mb-1.5">Lampiran (Bisa pilih lebih dari satu gambar)</label>
                
                <input type="file" id="attachments" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-gray-200 rounded-lg bg-gray-50">
                <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, PDF (Maks 10MB per file). Tahan tombol CTRL saat memilih untuk upload banyak.</p>
                
                <div id="image-preview-container" class="mt-4 flex flex-wrap justify-center gap-4 bg-gray-50 rounded-lg p-4 border border-gray-200 hidden">
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

<script>
    let selectedFiles = new DataTransfer();

    document.getElementById('attachments').addEventListener('change', function(event) {
        const files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            selectedFiles.items.add(files[i]);
        }

        document.getElementById('attachments').files = selectedFiles.files;

        renderPreview();
    });

    function renderPreview() {
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = '';

        if (selectedFiles.files.length > 0) {
            previewContainer.classList.remove('hidden');

            Array.from(selectedFiles.files).forEach((file, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'relative group';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold hover:bg-red-600 shadow-md opacity-0 group-hover:opacity-100 transition-opacity';
                removeBtn.innerHTML = 'X';
                removeBtn.onclick = function() {
                    removeFile(index);
                };

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-32 w-auto object-contain border border-gray-300 rounded shadow-sm bg-white p-1';
                        itemDiv.appendChild(img);
                        itemDiv.appendChild(removeBtn);
                        previewContainer.appendChild(itemDiv);
                    }
                    reader.readAsDataURL(file);
                } else {
                    const docBox = document.createElement('div');
                    docBox.className = 'h-32 w-32 flex flex-col items-center justify-center border border-gray-300 rounded shadow-sm bg-white p-2 text-center';
                    docBox.innerHTML = '<span class="font-bold text-gray-400 mb-1">FILE</span><span class="text-xs text-gray-600 break-all line-clamp-2">' + file.name + '</span>';
                    itemDiv.appendChild(docBox);
                    itemDiv.appendChild(removeBtn);
                    previewContainer.appendChild(itemDiv);
                }
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    function removeFile(index) {
        const dt = new DataTransfer();
        const files = selectedFiles.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        selectedFiles = dt;
        document.getElementById('attachments').files = selectedFiles.files; 
        renderPreview();
    }
</script>
<script>
    let isSubmitting = false;

    document.getElementById('memoForm').addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        isSubmitting = true; 
        
        const buttons = this.querySelectorAll('button[type="submit"]');
        buttons.forEach(btn => {
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.style.pointerEvents = 'none';
            
            if (btn.innerText.includes('Draft')) {
                btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Menyimpan...';
            } else {
                btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Menerbitkan...';
            }
        });
        lucide.createIcons();
    });
</script>
@endsection