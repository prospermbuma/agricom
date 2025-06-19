<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    // Relationships
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function farmerProfiles()
    {
        return $this->hasMany(FarmerProfile::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'region', 'name');
    }
}
