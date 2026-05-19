<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumDraft extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'curriculum_drafts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'blueprint_id',
        'chapter_id',
        'payload',
        'last_saved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
        'last_saved_at' => 'datetime',
    ];

    /**
     * Get the user (SME) that owns the curriculum draft.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the blueprint associated with the draft.
     */
    public function blueprint()
    {
        return $this->belongsTo(TrainingBlueprint::class, 'blueprint_id', 'id');
    }
}
