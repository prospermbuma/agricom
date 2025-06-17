<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    public function run()
    {
        $villages = [
            ['name' => 'Kikuyu', 'region_id' => 1],
            ['name' => 'Majengo', 'region_id' => 2],
            ['name' => 'Iyunga', 'region_id' => 3],
            ['name' => 'Nyamagana', 'region_id' => 4]
        ];

        foreach ($villages as $village) {
            Village::create($village);
        }
    }
}
