<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemoRequest;
use App\Models\Department;
use App\Models\Memo;
use App\Models\MemoAttachment;
use App\Models\MemoHistory;
use App\Models\MemoRecipient;
use App\Services\MemoNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MemoController extends Controller
{
    public function index(): View
    {
        $memos = Memo::where('sender_user_id', auth()->id())
            ->with(['recipients.targetDepartment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('memos.index', compact('memos'));
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)
            ->where('id', '!=', auth()->user()->department_id)
            ->orderBy('name')
            ->get();

        return view('memos.create', compact('departments'));
    }

    public function store(StoreMemoRequest $request, MemoNumberService $numberService): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();
        $isSend = $request->input('action_type') === 'send';

        DB::beginTransaction();
        try {
            $originDept = $user->department;
            $targetDept = Department::findOrFail($validated['target_department_id']);

            $memo = new Memo();
            $memo->sender_user_id = $user->id;
            $memo->origin_department_id = $originDept->id;
            $memo->subject = $validated['subject'];
            $memo->content = $validated['content'];
            $memo->priority = $validated['priority'];
            $memo->status = $isSend ? 'in_progress' : 'draft';

            if ($isSend) {
                $numberData = $numberService->generateNumber($originDept, $targetDept);
                $memo->memo_number = $numberData['memo_number'];
                $memo->sequence_number = $numberData['sequence_number'];
                $memo->published_date = $numberData['published_date'];
            }

            $memo->save();

            MemoRecipient::create([
                'memo_id' => $memo->id,
                'target_department_id' => $targetDept->id,
                'status' => 'pending',
                'received_at' => $isSend ? now() : null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('memo_attachments', 'public');
                    MemoAttachment::create([
                        'memo_id' => $memo->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_mime' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            MemoHistory::create([
                'memo_id' => $memo->id,
                'user_id' => $user->id,
                'department_id' => $originDept->id,
                'action' => $isSend ? 'SENT' : 'CREATED_DRAFT',
                'description' => $isSend 
                    ? "Memo resmi dikirim ke divisi {$targetDept->name}"
                    : "Draft memo dibuat",
            ]);

            DB::commit();

            $msg = $isSend ? 'Memo resmi berhasil diterbitkan!' : 'Draft memo berhasil disimpan.';
            return redirect()->route('memos.show', $memo->id)->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses memo: ' . $e->getMessage());
        }
    }

    public function show(Memo $memo): View
    {
        $memo->load(['sender', 'originDepartment', 'attachments', 'recipients.targetDepartment']);
        $recipient = $memo->recipients()->first();

        return view('memos.show', compact('memo', 'recipient'));
    }

    public function print(Memo $memo): View
    {
        $memo->load(['sender', 'originDepartment', 'attachments', 'recipients.targetDepartment']);
        $recipient = $memo->recipients()->first();

        return view('memos.print', compact('memo', 'recipient'));
    }
}