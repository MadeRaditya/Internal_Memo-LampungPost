<?php

namespace App\Policies;

use App\Models\Memo;
use App\Models\User;

class MemoPolicy
{
    public function view(User $user, Memo $memo): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()){
            return true;
        }

        if ($memo->sender_user_id === $user->id){
            return true;
        }

        if ($memo->origin_department_id === $user->department_id){
            return true;
        }

        return $memo->recipients()->where('target_department_id',$user->department_id)->exists();
    }

    public function update(User $user, Memo $memo): bool
    {
        return $memo->isDraft() && $memo->sender_user_id === $user->id;
    }
}