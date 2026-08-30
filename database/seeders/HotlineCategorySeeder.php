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
            'Hospitals',
        ];

        foreach ($categories as $category) {
            HotlineCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}
