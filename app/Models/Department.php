<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function originMemos(): HasMany
    {
        return $this->hasMany(Memo::class, 'origin_department_id');
    }

    public function targetRecipients(): HasMany
    {
        return $this->hasMany(MemoRecipient::class, 'target_department_id');
    }
}
