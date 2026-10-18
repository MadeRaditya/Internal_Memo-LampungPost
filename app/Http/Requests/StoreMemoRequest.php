<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'target_department_id' => ['required', 'exists:departments,id'],
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'content' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg', 'max:10240'],
            'action_type' => ['required', 'in:draft,send'],
        ];
    }

    public function messages(): array
    {
        return [
            'target_department_id.required' => 'Pilih divisi tujuan memo.',
            'subject.required' => 'Perihal memo wajib diisi.',
            'priority.required' => 'Pilih prioritas memo.',
            'content.required' => 'Isi memo tidak boleh kosong.',
            'attachments.*.max' => 'Ukuran setiap lampiran maksimal 10MB.',
        ];
    }
}