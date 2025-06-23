<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run()
    {
        $crops = [
            [
                'name' => 'Maize',
                'scientific_name' => 'Zea mays',
                'description' => 'Main staple crop in Tanzania',
                'season' => 'rainy',
                'growing_period_days' => 120,
            ],
            [
                'name' => 'Rice',
                'scientific_name' => 'Oryza sativa',
                'description' => 'Important cereal crop',
                'season' => 'rainy',
                'growing_period_days' => 150,
            ],
            [
                'name' => 'Beans',
                'scientific_name' => 'Phaseolus vulgaris',
                'description' => 'Legume crop rich in protein',
                'season' => 'both',
                'growing_period_days' => 90,
            ],
            [
                'name' => 'Coffee',
                'scientific_name' => 'Coffea arabica',
                'description' => 'Cash crop for export',
                'season' => 'both',
                'growing_period_days' => 365,
            ],
            [
                'name' => 'Cotton',
                'scientific_name' => 'Gossypium hirsutum',
                'description' => 'Cash crop for textile industry',
                'season' => 'rainy',
                'growing_period_days' => 180,
            ],
            [
                'name' => 'Cassava',
                'scientific_name' => 'Manihot esculenta',
                'description' => 'Drought-resistant root crop',
                'season' => 'both',
                'growing_period_days' => 240,
            ],
            [
                'name' => 'Sweet Potatoes',
                'scientific_name' => 'Ipomoea batatas',
                'description' => 'Nutritious root crop',
                'season' => 'both',
                'growing_period_days' => 120,
            ],
            [
                'name' => 'Sorghum',
                'scientific_name' => 'Sorghum bicolor',
                'description' => 'Drought-tolerant cereal crop',
                'season' => 'dry',
                'growing_period_days' => 100,
            ],
        ];

        foreach ($crops as $crop) {
            Crop::updateOrCreate(
                ['name' => $crop['name']],
                $crop
            );
        }
    }
}