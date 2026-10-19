<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\MemoHistory;
use App\Models\MemoRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InboxController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $inboxes = MemoRecipient::with(['memo.sender', 'memo.originDepartment'])
            ->where('target_department_id', $user->department_id)
            ->whereHas('memo', function ($query) {
                $query->where('status', '!=', 'draft');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inbox.index', compact('inboxes'));
    }

    public function show(MemoRecipient $recipient): View
    {
        $user = auth()->user();

        if ($recipient->target_department_id !== $user->department_id) {
            abort(403, 'Anda tidak memiliki akses ke memo ini.');
        }

        if (is_null($recipient->read_at)) {
            $recipient->update([
                'read_at' => now(),
                'status' => 'read'
            ]);

            MemoHistory::create([
                'memo_id' => $recipient->memo_id,
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'action' => 'READ',
                'description' => "Memo dibaca oleh {$user->name} dari divisi {$user->department->name}",
            ]);
        }

        $memo = $recipient->memo;
        $departments = Department::where('is_active', true)
            ->where('id', '!=', $user->department_id)
            ->get();

        return view('inbox.show', compact('recipient', 'memo', 'departments'));
    }

    public function process(Request $request, MemoRecipient $recipient)
    {
        $user = auth()->user();

        if ($recipient->target_department_id !== $user->department_id) {
            abort(403);
        }

        $request->validate([
            'action_type' => 'required|in:complete,forward',
            'response_notes' => 'nullable|string',
            'forward_department_id' => 'required_if:action_type,forward|exists:departments,id|nullable',
        ]);

        DB::beginTransaction();
        try {
            $isForward = $request->action_type === 'forward';
            $status = $isForward ? 'forwarded' : 'completed';

            $recipient->update([
                'status' => $status,
                'responded_at' => now(),
                'response_notes' => $request->response_notes,
                'processed_by_user_id' => $user->id,
            ]);

            if ($isForward) {
                $targetDept = Department::find($request->forward_department_id);
                MemoRecipient::create([
                    'memo_id' => $recipient->memo_id,
                    'target_department_id' => $targetDept->id,
                    'parent_recipient_id' => $recipient->id,
                    'status' => 'pending',
                    'received_at' => now(),
                ]);
            }

            if (!$isForward) {
                $recipient->memo->update(['status' => 'completed']);
            }

            $actionStr = $isForward ? 'FORWARDED' : 'COMPLETED';
            $descStr = $isForward
                ? "Memo diteruskan ke {$targetDept->name}. Catatan: {$request->response_notes}"
                : "Memo diselesaikan. Catatan: {$request->response_notes}";

            MemoHistory::create([
                'memo_id' => $recipient->memo_id,
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'action' => $actionStr,
                'description' => $descStr,
            ]);

            DB::commit();

            $msg = $isForward ? 'Memo berhasil diteruskan.' : 'Memo berhasil diselesaikan.';
            return redirect()->route('inbox.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses memo: ' . $e->getMessage());
        }
    }
}
