<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FarmerProfile;
use App\Models\Region;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@agriculture.gov.tz'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+255123456789',
            ]
        );

        // Create sample VEO
        User::updateOrCreate(
            ['email' => 'john.veo@agriculture.gov.tz'],
            [
                'name' => 'John Mwalimu',
                'password' => Hash::make('password'),
                'role' => 'veo',
                'phone' => '+255123456790',
                'bio' => 'Village Extension Officer with 10 years experience',
            ]
        );

        // Create sample farmers
        $farmers = [
            [
                'name' => 'Mary Kiprotich',
                'email' => 'mary.farmer@gmail.com',
                'role' => 'farmer',
                'phone' => '+255123456791',
            ],
            [
                'name' => 'Peter Mwanga',
                'email' => 'peter.farmer@gmail.com',
                'role' => 'farmer',
                'phone' => '+255123456792',
            ],
        ];

        foreach ($farmers as $farmerData) {
            $user = User::updateOrCreate(
                ['email' => $farmerData['email']],
                [
                    ...$farmerData,
                    'password' => Hash::make('password'),
                ]
            );

            // Create farmer profile
            FarmerProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'region_id' => Region::first()->id,
                    'village_id' => Village::first()->id,
                    'farm_size_acres' => rand(1, 10),
                    'farming_experience' => 'intermediate',
                    'farming_methods' => 'Traditional and modern techniques',
                ]
            );
        }
    }
}