<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Memo;

class MemoNumberService
{
    private function getRomanMonth(int $month): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
            6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
            11 => 'XI', 12 => 'XII'
        ];
        return $map[$month] ?? 'I';
    }

    public function generateNumber(Department $originDept, Department $targetDept, string $date = null): array
    {
        $publishedDate = $date ? \Carbon\Carbon::parse($date) : now();
        $year = $publishedDate->format('Y');
        $monthRoman = $this->getRomanMonth((int) $publishedDate->format('m'));
        $day = $publishedDate->format('d');

        $baseFormat = sprintf(
            'Nm. %s-%s-%s DEPT-%s-%s',
            $year,
            $monthRoman,
            $day,
            $originDept->code,
            $targetDept->code
        );

        $lastMemo = Memo::where('origin_department_id', $originDept->id)
            ->whereDate('published_date', $publishedDate->toDateString())
            ->whereNotNull('memo_number')
            ->lockForUpdate()
            ->orderBy('sequence_number', 'desc')
            ->first();

        $nextSequence = $lastMemo ? ($lastMemo->sequence_number + 1) : 1;

        $finalMemoNumber = $nextSequence > 1 
            ? sprintf('%s/%02d', $baseFormat, $nextSequence)
            : $baseFormat;

        return [
            'memo_number' => $finalMemoNumber,
            'sequence_number' => $nextSequence,
            'published_date' => $publishedDate->toDateString(),
        ];
    }
}