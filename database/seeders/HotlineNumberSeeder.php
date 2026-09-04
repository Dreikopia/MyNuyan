<?php

namespace Database\Seeders;

use App\Models\Hotline;
use App\Models\HotlineNumber;
use Illuminate\Database\Seeder;

class HotlineNumberSeeder extends Seeder
{
    public function run(): void
    {
        $numbers = [
            [
                'hotline' => 'National Emergency Hotline',
                'number' => '911',
                'type' => 'Emergency',
                'label' => 'Emergency Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Minuyan Proper Police Station',
                'number' => '0998 598 8110',
                'type' => 'Mobile',
                'label' => 'Police Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Bureau of Fire Protection',
                'number' => '(044) 931 0084',
                'type' => 'Landline',
                'label' => 'Fire Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Emergency Medical Services',
                'number' => '911',
                'type' => 'Emergency',
                'label' => 'Emergency Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Barangay Hall',
                'number' => '0912 345 6789',
                'type' => 'Mobile',
                'label' => 'Barangay Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'MDRRMO',
                'number' => '0917 123 4567',
                'type' => 'Mobile',
                'label' => 'Rescue Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Barangay Peace and Order',
                'number' => '0918 234 5678',
                'type' => 'Mobile',
                'label' => 'Peace and Order Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Water & Utility Services',
                'number' => '1627',
                'type' => 'Hotline',
                'label' => 'Utility Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'DSWD Assistance',
                'number' => '(02) 8733 0010',
                'type' => 'Landline',
                'label' => 'DSWD Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'City Government Hotline',
                'number' => '1234',
                'type' => 'Hotline',
                'label' => 'City Government Hotline',
                'is_primary' => true,
            ],
            [
                'hotline' => 'Community Assistance',
                'number' => '0919 345 6789',
                'type' => 'Mobile',
                'label' => 'Community Hotline',
                'is_primary' => true,
            ],
        ];

        foreach ($numbers as $number) {
            $hotline = Hotline::where(
                'name',
                $number['hotline']
            )->first();

            if (! $hotline) {
                continue;
            }

            HotlineNumber::updateOrCreate(
                [
                    'hotline_id' => $hotline->id,
                    'number' => $number['number'],
                ],
                [
                    'type' => $number['type'],
                    'label' => $number['label'],
                    'is_primary' => $number['is_primary'],
                ]
            );
        }
    }
}
