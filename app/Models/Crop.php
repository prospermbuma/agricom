<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'season',
        'growing_period_days',
        'image'
    ];

    public function farmerCrops()
    {
        return $this->hasMany(FarmerCrop::class);
    }

    public function farmers()
    {
        return $this->belongsToMany(FarmerProfile::class, 'farmer_crops');
    }
}