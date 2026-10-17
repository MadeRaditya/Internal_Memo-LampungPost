<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemoRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_id',
        'target_department_id',
        'parent_recipient_id',
        'status',
        'received_at',
        'read_at',
        'responded_at',
        'response_notes',
        'processed_by_user_id',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function memo(): BelongsTo
    {
        return $this->belongsTo(Memo::class);
    }

    public function targetDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'target_department_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    public function parentRecipient(): BelongsTo
    {
        return $this->belongsTo(MemoRecipient::class, 'parent_recipient_id');
    }

    public function forwardedRecipients(): HasMany
    {
        return $this->hasMany(MemoRecipient::class, 'parent_recipient_id');
    }
}
