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
        public string $raw_date,
        public string $description,
        public int $participants,
        public array $documents = [],
        public array $participants_list = [],
        public ?string $feedback = null,
        public ?string $feedback_by = null,
        public ?string $proposer_name = null,
        public ?string $company_name = null,
        public ?int $company_id = null,
        public ?int $organization_id = null,
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
            raw_date: $submission->submission_date->format('Y-m-d'),
            description: $submission->description ?? '',
            participants: $submission->participants ?? 0,
            documents: collect($submission->documents ?? [])->map(function($doc) {
                if (isset($doc['path'])) {
                    $doc['url'] = asset('storage/' . $doc['path']);
                }
                return $doc;
            })->toArray(),
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
            })->toArray(),
            feedback: $submission->feedback,
            feedback_by: $submission->feedback_by,
            proposer_name: $submission->user->name ?? 'System',
            company_name: $submission->user ? ($submission->user->getOrganizationPath()->first()->name ?? '-') : '-',
            company_id: $submission->user ? ($submission->user->getOrganizationPath()->first()->id ?? null) : null,
            organization_id: $submission->user ? $submission->user->organization_id : null,
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
            'raw_date' => $this->raw_date,
            'description' => $this->description,
            'participants' => $this->participants,
            'participants_list' => $this->participants_list,
            'documents' => $this->documents,
            'feedback' => $this->feedback,
            'feedback_by' => $this->feedback_by,
            'proposer_name' => $this->proposer_name,
            'company_name' => $this->company_name,
            'company_id' => $this->company_id,
            'organization_id' => $this->organization_id,
        ];
    }
}
