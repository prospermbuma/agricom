<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    public function run()
    {
        $villages = [
            ['name' => 'Kikuyu', 'region_id' => 1, 'code' => 'KIK'],
            ['name' => 'Majengo', 'region_id' => 2, 'code' => 'MAJ'],
            ['name' => 'Iyunga', 'region_id' => 3, 'code' => 'IYU'],
            ['name' => 'Nyamagana', 'region_id' => 4, 'code' => 'NYA']
        ];

        foreach ($villages as $village) {
            Village::create($village);
        }
    }
}
