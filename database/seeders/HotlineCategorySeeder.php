<?php

namespace Database\Seeders;

use App\Models\HotlineCategory;
use Illuminate\Database\Seeder;

class HotlineCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Emergency',
            'Police',
            'Fire & Rescue',
            'Medical',
            'Barangay',
            'Disaster & Rescue',
            'Peace & Order',
            'Utilities',
            'Social Services',
            'Government',
            'Other',
        ];

        foreach ($categories as $category) {
            HotlineCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}
