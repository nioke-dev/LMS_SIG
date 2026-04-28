<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'org_level_id', 'parent_id'];

    public function level()
    {
        return $this->belongsTo(OrgLevel::class, 'org_level_id');
    }

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all descendant users (recursive)
     */
    public function getAllDescendantUsers()
    {
        $userIds = $this->users()->pluck('id')->toArray();
        
        foreach ($this->children as $child) {
            $userIds = array_merge($userIds, $child->getAllDescendantUsers());
        }
        
        return $userIds;
    }
}
