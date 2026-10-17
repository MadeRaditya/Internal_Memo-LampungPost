<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MemoAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_id',
        'file_path',
        'original_name',
        'file_mime',
        'file_size',
    ];

    public function memo(): BelongsTo
    {
        return $this->belongsTo(Memo::class);
    }

    protected function formattedSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                $bytes = $this->file_size;
                if ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    return number_format($bytes / 1024, 2) . ' KB';
                }
                return $bytes . ' bytes';
            }
        );
    }
}
