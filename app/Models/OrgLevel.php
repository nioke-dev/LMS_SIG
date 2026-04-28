<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgLevel extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'name', 'order'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
