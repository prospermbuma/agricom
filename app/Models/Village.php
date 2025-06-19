<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region_id',
        'code',
        'latitude',
        'longitude',
        'population'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function farmerProfiles()
    {
        return $this->hasMany(FarmerProfile::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'village', 'name');
    }
}
