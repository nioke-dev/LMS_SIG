<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function levels()
    {
        return $this->hasMany(OrgLevel::class)->orderBy('order');
    }

    public function organizations()
    {
        return $this->hasManyThrough(Organization::class, OrgLevel::class);
    }
}
