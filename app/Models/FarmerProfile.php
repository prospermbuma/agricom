<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FarmerProfile extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id', 'region_id', 'village_id', 'farm_size_acres',
        'farming_experience', 'farming_methods'
    ];

    protected $casts = [
        'farm_size_acres' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['region_id', 'village_id', 'farm_size_acres', 'farming_experience'])
            ->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function farmerCrops()
    {
        return $this->hasMany(FarmerCrop::class);
    }

    public function crops()
    {
        return $this->belongsToMany(Crop::class, 'farmer_crops');
    }
}