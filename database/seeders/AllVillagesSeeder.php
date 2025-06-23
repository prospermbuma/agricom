<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Village;
use League\Csv\Reader;

class AllVillagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Place your CSV at database/data/tanzania_villages.csv with columns: region_name,village_name
     */
    public function run()
    {
        $csvPath = database_path('data/tanzania_villages.csv');
        if (!file_exists($csvPath)) {
            $this->command->error('CSV file not found: ' . $csvPath);
            return;
        }

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $record) {
            $regionName = trim($record['region_name']);
            $villageName = trim($record['village_name']);
            if (!$regionName || !$villageName) continue;

            $region = Region::firstOrCreate(['name' => $regionName]);
            Village::firstOrCreate([
                'name' => $villageName,
                'region_id' => $region->id,
            ]);
        }
    }
} 