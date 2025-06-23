<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerCrop extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_profile_id',
        'crop_id',
        'area_planted_acres',
        'planting_date',
        'expected_harvest_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'area_planted_acres' => 'decimal:2',
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
    ];

    // Relationships
    public function farmerProfile()
    {
        return $this->belongsTo(FarmerProfile::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHarvested($query)
    {
        return $query->where('status', 'harvested');
    }

    public function scopePlanted($query)
    {
        return $query->where('status', 'planted');
    }

    public function scopeByCrop($query, $cropId)
    {
        return $query->where('crop_id', $cropId);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isHarvested(): bool
    {
        return $this->status === 'harvested';
    }

    public function isPlanted(): bool
    {
        return $this->status === 'planted';
    }

    public function markAsHarvested(): bool
    {
        return $this->update(['status' => 'harvested']);
    }

    public function markAsActive(): bool
    {
        return $this->update(['status' => 'active']);
    }

    public function getDaysSincePlanting(): int
    {
        if (!$this->planting_date) {
            return 0;
        }
        return $this->planting_date->diffInDays(now());
    }

    public function getDaysUntilHarvest(): int
    {
        if (!$this->expected_harvest_date) {
            return 0;
        }
        return now()->diffInDays($this->expected_harvest_date, false);
    }
}
