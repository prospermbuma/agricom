<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run()
    {
        $regions = [
            ['name' => 'Arusha', 'code' => 'AR'],
            ['name' => 'Dar es Salaam', 'code' => 'DS'],
            ['name' => 'Dodoma', 'code' => 'DO'],
            ['name' => 'Mwanza', 'code' => 'MW'],
            ['name' => 'Morogoro', 'code' => 'MR'],
            ['name' => 'Mbeya', 'code' => 'MB'],
            ['name' => 'Kilimanjaro', 'code' => 'KL'],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
