<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Models\MemoRecipient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $deptId = $user->department_id;

        $inboxCount = MemoRecipient::where('target_department_id', $deptId)
            ->whereHas('memo', function ($query) {
                $query->where('status', '!=', 'draft');
            })
            ->count();

        $unreadCount = MemoRecipient::where('target_department_id', $deptId)
            ->whereNull('read_at')
            ->whereHas('memo', function ($query) {
                $query->where('status', '!=', 'draft');
            })
            ->count();

        $sentCount = Memo::where('sender_user_id', $user->id)
            ->where('status', '!=', 'draft')
            ->count();
        
        $draftCount = Memo::where('sender_user_id', $user->id)
            ->where('status', 'draft')
            ->count();

        $recentInboxes = MemoRecipient::with(['memo.originDepartment'])
            ->where('target_department_id', $deptId)
            ->whereHas('memo', function ($query) {
                $query->where('status', '!=', 'draft');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('inboxCount', 'unreadCount', 'sentCount', 'draftCount', 'recentInboxes'));
    }
}