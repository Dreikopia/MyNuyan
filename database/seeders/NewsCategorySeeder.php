<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Announcement',
            'Health',
            'Events',
            'Infrastracture',
            'Program',
        ];

        foreach ($categories as $category) {
            NewsCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}
