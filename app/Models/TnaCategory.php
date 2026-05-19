<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TnaCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(TnaCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(TnaCategory::class, 'parent_id');
    }
}
