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
            'Noise Complaint',
            'Domestic Dispute',
            'Public Disturbance',
            'Illegal Parking',
            'Garbage Disposal',
            'Drainage Problem',
            'Road Damage',
            'Street Light Repair',
            'Water Supply Issue',
            'Electricity Concern',
            'Stray Animals',
            'Illegal Construction',
            'Environmental Concern',
            'Business Permit Violation',
            'Others',
        ];
        foreach ($categories as $category) {
            ComplaintCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}
