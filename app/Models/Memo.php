<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'memo_number',
        'sender_user_id',
        'origin_department_id',
        'subject',
        'content',
        'priority',
        'status',
        'published_date',
        'sequence_number',
    ];

    protected $casts = [
        'published_date' => 'date',
        'sequence_number' => 'integer',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function originDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'origin_department_id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(MemoRecipient::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MemoAttachment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(MemoHistory::class)->orderBy('created_at', 'asc');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status !== 'draft';
    }
}
