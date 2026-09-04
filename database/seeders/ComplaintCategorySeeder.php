<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Noise Complaint'],
            ['name' => 'Domestic Dispute'],
            ['name' => 'Public Disturbance'],
            ['name' => 'Illegal Parking'],
            ['name' => 'Garbage Disposal'],
            ['name' => 'Drainage Problem'],
            ['name' => 'Road Damage'],
            ['name' => 'Street Light Repair'],
            ['name' => 'Water Supply Issue'],
            ['name' => 'Electricity Concern'],
            ['name' => 'Stray Animals'],
            ['name' => 'Illegal Construction'],
            ['name' => 'Environmental Concern'],
            ['name' => 'Business Permit Violation'],
            ['name' => 'Others'],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::updateOrCreate(
                ['name' => $category['name']]
            );
        }
    }
}
