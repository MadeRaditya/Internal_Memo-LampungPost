@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Header & Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('memos.show', $memo->id) }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors" title="Kembali ke Pratinjau Memo" aria-label="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Edit Draft Memo</h1>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Perbarui rincian konsep memo sebelum resmi diterbitkan ke divisi tujuan.</p>
            </div>
        </div>
    </div>

    <!-- Main Edit Form -->
    <form id="memoForm" method="POST" action="{{ route('memos.update', $memo->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Informasi Pengiriman -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-900">1. Alamat Pengiriman Memo</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Origin Department -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                        Divisi Asal (Pengirim)
                    </label>
                    <div class="flex items-center gap-2.5 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm font-medium">
                        <i data-lucide="building" class="w-4 h-4 text-slate-400"></i>
                        <span>{{ auth()->user()->department->name ?? 'Divisi Umum' }}</span>
                    </div>
                </div>

                <!-- Target Department -->
                <div>
                    <label for="target_department_id" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                        Kepada Yth. (Divisi Tujuan) <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <select id="target_department_id" name="target_department_id" required 
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('target_department_id') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                        <option value="">-- Pilih Divisi Tujuan --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('target_department_id', $recipient->target_department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('target_department_id')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Isi & Perihal Memo -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-900">2. Perihal & Rincian Pengajuan</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Subject -->
                <div class="sm:col-span-2">
                    <label for="subject" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                        Perihal Memo <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $memo->subject) }}" required 
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('subject') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">
                    @error('subject')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                        Tingkat Prioritas
                    </label>
                    <select id="priority" name="priority" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-xl text-sm text-slate-900 outline-none transition-all">
                        <option value="normal" {{ old('priority', $memo->priority) === 'normal' ? 'selected' : '' }}>Biasa (Normal)</option>
                        <option value="high" {{ old('priority', $memo->priority) === 'high' ? 'selected' : '' }}>Penting (High)</option>
                        <option value="urgent" {{ old('priority', $memo->priority) === 'urgent' ? 'selected' : '' }}>Mendesak (Urgent)</option>
                    </select>
                </div>
            </div>

            <!-- Content Area -->
            <div>
                <label for="content" class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-1.5">
                    Isi Pengajuan / Inti Surat <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <textarea id="content" name="content" rows="5" required 
                          class="w-full px-4 py-3 bg-slate-50 border @error('content') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-300 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @enderror rounded-xl text-sm text-slate-900 outline-none transition-all">{{ old('content', $memo->content) }}</textarea>
                @error('content')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section 3: Lampiran Berkas & Gambar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="paperclip" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">3. Berkas & Lampiran Dokumen</h2>
                    <p class="text-xs text-slate-400">Kelola berkas yang sudah ada atau tambahkan berkas baru</p>
                </div>
            </div>

            <!-- Existing Attachments -->
            @if ($memo->attachments->count() > 0)
                <div>
                    <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-3">
                        Lampiran yang Tersimpan Saat Ini
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4" id="old-attachments-container">
                        @foreach ($memo->attachments as $attachment)
                            <div class="relative p-2 rounded-xl border border-slate-200 bg-slate-50 group flex flex-col items-center justify-center transition-all" id="old-attachment-{{ $attachment->id }}">
                                <button type="button" onclick="removeOldAttachment({{ $attachment->id }})"
                                    class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold hover:bg-rose-700 shadow-sm cursor-pointer z-10 transition-transform hover:scale-110"
                                    title="Hapus lampiran ini">
                                    &times;
                                </button>

                                @if (str_starts_with($attachment->file_mime, 'image/'))
                                    <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                        class="h-28 w-full object-cover rounded-lg border border-slate-200 bg-white mb-1.5"
                                        alt="{{ $attachment->original_name }}">
                                @else
                                    <div class="h-28 w-full flex flex-col items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-center mb-1.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-1">
                                            <i data-lucide="file-text" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-700 truncate w-full px-1">{{ $attachment->original_name }}</span>
                                    </div>
                                @endif
                                <p class="text-[10px] font-medium text-slate-600 truncate w-full text-center px-1">{{ $attachment->original_name }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div id="deleted-attachments-inputs"></div>
                </div>
            @endif

            <!-- Add New Attachments Dropzone -->
            <div class="pt-2">
                <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-2">
                    Tambahkan Lampiran Baru (Opsional)
                </label>
                <label for="attachments" class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl cursor-pointer bg-slate-50/50 hover:bg-blue-50/20 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-800">Pilih berkas dari komputer</span>
                    <span class="text-xs text-slate-400 mt-1">Mendukung PNG, JPG, PDF (hingga 10MB per berkas)</span>
                    <input type="file" id="attachments" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx" class="hidden">
                </label>
            </div>

            <!-- Preview Gallery of New Selected Files -->
            <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-4 gap-4 hidden pt-2"></div>
        </div>

        <!-- Bottom Action Bar -->
        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <a href="{{ route('memos.show', $memo->id) }}" class="text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Batal & Kembali
            </a>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button type="submit" name="action_type" value="draft" class="btn-action px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 active:scale-[0.99] text-xs sm:text-sm font-semibold transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Perbarui Draft</span>
                </button>

                <button type="button" onclick="confirmPublishMemo()" class="btn-action px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-xs sm:text-sm font-semibold transition-all shadow-sm shadow-blue-500/25 flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Terbitkan Memo Resmi</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Remove existing attachment with SweetAlert2 confirmation
    function removeOldAttachment(id) {
        Swal.fire({
            title: 'Hapus Lampiran Ini?',
            text: 'Lampiran ini akan ditandai untuk dihapus setelah formulir disimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Tandai Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-sm font-semibold px-4 py-2',
                cancelButton: 'rounded-xl text-sm font-semibold px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const item = document.getElementById('old-attachment-' + id);
                if (item) {
                    item.style.display = 'none';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_attachments[]';
                    input.value = id;
                    document.getElementById('deleted-attachments-inputs').appendChild(input);
                }
            }
        });
    }

    // Accumulate selected files without overwriting
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
                itemDiv.className = 'relative p-2 rounded-xl border border-slate-200 bg-slate-50 group flex flex-col items-center justify-center';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 bg-rose-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold hover:bg-rose-700 shadow-sm cursor-pointer z-10 transition-transform hover:scale-110';
                removeBtn.innerHTML = '&times;';
                removeBtn.title = 'Hapus lampiran ini';
                removeBtn.onclick = function() {
                    removeFile(index);
                };

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-28 w-full object-cover rounded-lg border border-slate-200 bg-white mb-1.5';
                        itemDiv.appendChild(img);

                        const nameSpan = document.createElement('p');
                        nameSpan.className = 'text-[10px] font-medium text-slate-600 truncate w-full text-center px-1';
                        nameSpan.textContent = file.name;
                        itemDiv.appendChild(nameSpan);

                        itemDiv.appendChild(removeBtn);
                        previewContainer.appendChild(itemDiv);
                    }
                    reader.readAsDataURL(file);
                } else {
                    const docBox = document.createElement('div');
                    docBox.className = 'h-28 w-full flex flex-col items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-center mb-1.5';
                    docBox.innerHTML = '<div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-1"><i data-lucide="file-text" class="w-4 h-4"></i></div><span class="text-[10px] font-bold text-slate-700 truncate w-full px-1">' + file.name + '</span><span class="text-[9px] text-slate-400">' + (file.size / 1024).toFixed(0) + ' KB</span>';
                    itemDiv.appendChild(docBox);
                    itemDiv.appendChild(removeBtn);
                    previewContainer.appendChild(itemDiv);
                    lucide.createIcons();
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
            if (i !== index) dt.items.add(files[i]);
        }
        selectedFiles = dt;
        document.getElementById('attachments').files = selectedFiles.files;
        renderPreview();
    }

    // Modal confirmation before publishing memo
    function confirmPublishMemo() {
        const form = document.getElementById('memoForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Terbitkan Memo Resmi?',
            text: 'Nomor surat resmi akan di-generate otomatis dan memo langsung dikirim ke divisi tujuan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Terbitkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-sm font-semibold px-4 py-2',
                cancelButton: 'rounded-xl text-sm font-semibold px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const hiddenAction = document.createElement('input');
                hiddenAction.type = 'hidden';
                hiddenAction.name = 'action_type';
                hiddenAction.value = 'send';
                form.appendChild(hiddenAction);
                
                triggerSubmitLoading(form, 'Menerbitkan...');
                form.submit();
            }
        });
    }

    // Handle double submission
    let isSubmitting = false;
    document.getElementById('memoForm').addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        triggerSubmitLoading(this, 'Memperbarui...');
    });

    function triggerSubmitLoading(form, loadingText) {
        isSubmitting = true;
        const buttons = form.querySelectorAll('.btn-action');
        buttons.forEach(btn => {
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btn.style.pointerEvents = 'none';
        });
    }
</script>
@endsection
