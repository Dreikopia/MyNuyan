<?php

namespace Database\Seeders;

use App\Models\Hotline;
use App\Models\HotlineCategory;
use Illuminate\Database\Seeder;

class HotlineSeeder extends Seeder
{
    public function run(): void
    {
        $hotlines = [
            [
                'category' => 'Emergency',
                'name' => 'National Emergency Hotline',
                'location' => 'Philippines',
            ],
            [
                'category' => 'Police',
                'name' => 'Minuyan Proper Police Station',
                'location' => 'Minuyan Proper',
            ],
            [
                'category' => 'Fire & Rescue',
                'name' => 'Bureau of Fire Protection',
                'location' => 'Minuyan Proper',
            ],
            [
                'category' => 'Medical',
                'name' => 'Emergency Medical Services',
                'location' => 'Minuyan Proper',
            ],
            [
                'category' => 'Barangay',
                'name' => 'Barangay Hall',
                'location' => 'Barangay Minuyan Proper II',
            ],
            [
                'category' => 'Disaster & Rescue',
                'name' => 'MDRRMO',
                'location' => 'San Jose del Monte',
            ],
            [
                'category' => 'Peace & Order',
                'name' => 'Barangay Peace and Order',
                'location' => 'Barangay Minuyan Proper II',
            ],
            [
                'category' => 'Utilities',
                'name' => 'Water & Utility Services',
                'location' => 'Minuyan Proper',
            ],
            [
                'category' => 'Social Services',
                'name' => 'DSWD Assistance',
                'location' => 'Bulacan',
            ],
            [
                'category' => 'Government',
                'name' => 'City Government Hotline',
                'location' => 'San Jose del Monte',
            ],
            [
                'category' => 'Other',
                'name' => 'Community Assistance',
                'location' => 'Barangay Minuyan Proper II',
            ],
        ];

        foreach ($hotlines as $hotline) {
            $category = HotlineCategory::where(
                'name',
                $hotline['category']
            )->first();

            if (! $category) {
                continue;
            }

            Hotline::updateOrCreate(
                [
                    'name' => $hotline['name'],
                ],
                [
                    'category_id' => $category->id,
                    'location' => $hotline['location'],
                    'is_active' => true,
                ]
            );
        }
    }
}
