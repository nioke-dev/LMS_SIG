<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_name',
        'type',
        'date',
        'rating',
        'participants_count',
        'eval_predicate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
