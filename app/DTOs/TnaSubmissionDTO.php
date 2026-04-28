<?php

namespace App\DTOs;

use App\Models\TnaSubmission;

class TnaSubmissionDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $category,
        public string $urgency,
        public string $status,
        public string $date,
        public string $description,
        public int $participants,
        public array $documents = [],
        public ?string $admin_feedback = null,
        public array $participants_list = []
    ) {}

    /**
     * Factory method to create DTO from TnaSubmission model
     */
    public static function fromModel(TnaSubmission $submission): self
    {
        return new self(
            id: $submission->id,
            title: $submission->title,
            category: $submission->category,
            urgency: $submission->urgency,
            status: $submission->status,
            date: $submission->submission_date->format('d M Y'),
            description: $submission->description,
            participants: $submission->participants,
            documents: $submission->documents ?? [],
            admin_feedback: $submission->feedback,
            participants_list: collect($submission->participants_list ?? [])->map(function($item) {
                // Handle Relational ID (New Format)
                if (is_numeric($item)) {
                    $user = \App\Models\User::find($item);
                    return [
                        'id' => $item,
                        'name' => $user->name ?? 'User Not Found',
                        'nik' => $user->nik ?? '-',
                        'position' => $user->position ?? '-'
                    ];
                }
                
                // Handle Legacy Object (Array Format)
                if (is_array($item)) {
                    return [
                        'id' => $item['id'] ?? null,
                        'name' => $item['name'] ?? 'Unknown',
                        'nik' => $item['nik'] ?? '-',
                        'position' => $item['position'] ?? ($item['jabatan'] ?? '-')
                    ];
                }

                return $item;
            })->toArray()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'urgency' => $this->urgency,
            'status' => $this->status,
            'date' => $this->date,
            'description' => $this->description,
            'participants' => $this->participants,
            'documents' => $this->documents,
            'admin_feedback' => $this->admin_feedback,
            'participants_list' => $this->participants_list,
        ];
    }
}
