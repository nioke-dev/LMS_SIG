<?php

namespace App\Services;

use App\Models\TnaSubmission;
use App\Models\TnaCategory;
use App\Models\User;
use App\DTOs\TnaSubmissionDTO;
use App\DTOs\TnaCategoryDTO;
use App\Exceptions\TnaSubmissionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Exception;

class TnaService
{
    /**
     * Get all categories for TNA form.
     */
    public function getAllCategories(): Collection
    {
        return TnaCategory::all()->map(fn($cat) => TnaCategoryDTO::fromModel($cat));
    }

    /**
     * Store a new TNA submission with transaction and error handling.
     */
    public function storeSubmission(array $data): TnaSubmissionDTO
    {
        DB::beginTransaction();

        try {
            $id = $this->generateTnaId();

            $submission = TnaSubmission::create([
                'id' => $id,
                'title' => $data['title'],
                'submission_date' => now(),
                'category' => $data['category'] ?? '-',
                'urgency' => $data['urgency'] ?? 'Medium',
                'status' => $data['status'] ?? 'review',
                'description' => $data['description'] ?? '',
                'participants' => $data['participants_count'] ?? 0,
                'participants_list' => collect($data['participants_list'] ?? [])->pluck('id')->toArray(),
                'documents' => $data['documents'] ?? [],
            ]);

            DB::commit();

            return TnaSubmissionDTO::fromModel($submission);

        } catch (Exception $e) {
            DB::rollBack();
            throw new TnaSubmissionException("Gagal menyimpan usulan TNA: " . $e->getMessage());
        }
    }

    /**
     * Update an existing TNA submission.
     */
    public function updateSubmission(string $id, array $data): TnaSubmissionDTO
    {
        DB::beginTransaction();

        try {
            $submission = TnaSubmission::findOrFail($id);

            $submission->update([
                'title' => $data['title'],
                'category' => $data['category'] ?? $submission->category,
                'urgency' => $data['urgency'] ?? $submission->urgency,
                'status' => $data['status'] ?? $submission->status,
                'description' => $data['description'] ?? $submission->description,
                'participants' => $data['participants_count'] ?? $submission->participants,
                'participants_list' => isset($data['participants_list']) 
                    ? collect($data['participants_list'])->pluck('id')->toArray() 
                    : $submission->participants_list,
                'documents' => $data['documents'] ?? $submission->documents,
            ]);

            DB::commit();

            return TnaSubmissionDTO::fromModel($submission);

        } catch (Exception $e) {
            DB::rollBack();
            throw new TnaSubmissionException("Gagal memperbarui usulan TNA: " . $e->getMessage());
        }
    }

    /**
     * Logic for generating unique TNA ID.
     */
    public function generateTnaId(): string
    {
        $year = date('Y');
        $lastSubmission = TnaSubmission::where('id', 'like', "TNA-$year-GRS-%")
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = 1;
        if ($lastSubmission) {
            $lastIdParts = explode('-', $lastSubmission->id);
            $nextNumber = (int) end($lastIdParts) + 1;
        }

        return "TNA-$year-GRS-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
