<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingBlueprint extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'tna_submission_ids',
        'title',
        'category',
        'objective',
        'content',
        'sme_id',
        'sme_instructions',
        'need_workshop',
        'workshop_note',
        'deadline',
        'reminder_setting',
        'reminder_frequency',
        'distribution',
        'rationalization',
        'supporting_documents',
        'status',
        'cld_review_notes',
        'sme_submitted_materials',
        'sme_submitted_templates',
        'sme_submission_notes',
        'curriculum_structure',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tna_submission_ids' => 'array',
        'supporting_documents' => 'array',
        'sme_submitted_materials' => 'array',
        'sme_submitted_templates' => 'array',
        'curriculum_structure' => 'array',
        'need_workshop' => 'boolean',
        'deadline' => 'date',
    ];

    /**
     * Get the SME assigned to this blueprint.
     */
    public function sme()
    {
        return $this->belongsTo(User::class, 'sme_id');
    }
}
