<?php

namespace Database\Seeders;

use App\Models\Hotline;
use App\Models\HotlineCategory;
use Illuminate\Database\Seeder;

class HotlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotlines = [
            [
                'category' => 'Emergency',
                'name' => 'National Emergency Hotline',
                'phone_number' => '911',
            ],
            [
                'category' => 'Police',
                'name' => 'Minuyan Proper Police Station',
                'phone_number' => '0998 598 8110',
            ],
            [
                'category' => 'Fire & Rescue',
                'name' => 'Bureau of Fire Protection',
                'phone_number' => '(044) 931 0084',
            ],
            [
                'category' => 'Medical',
                'name' => 'Emergency Medical Services',
                'phone_number' => '911',
            ],
            [
                'category' => 'Barangay',
                'name' => 'Barangay Hall',
                'phone_number' => '0912 345 6789',
            ],
            [
                'category' => 'Disaster & Rescue',
                'name' => 'MDRRMO',
                'phone_number' => '0917 123 4567',
            ],
            [
                'category' => 'Peace & Order',
                'name' => 'Barangay Peace and Order',
                'phone_number' => '0918 234 5678',
            ],
            [
                'category' => 'Utilities',
                'name' => 'Water & Utility Services',
                'phone_number' => '1627',
            ],
            [
                'category' => 'Social Services',
                'name' => 'DSWD Assistance',
                'phone_number' => '(02) 8733 0010',
            ],
            [
                'category' => 'Government',
                'name' => 'City Government Hotline',
                'phone_number' => '1234',
            ],
            [
                'category' => 'Other',
                'name' => 'Community Assistance',
                'phone_number' => '0919 345 6789',
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
                    'admin_id' => 1,
                    'hotline_category_id' => $category->id,
                    'phone_number' => $hotline['phone_number'],
                ]
            );
        }
    }
}
