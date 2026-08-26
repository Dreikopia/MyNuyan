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
            [
                'name' => 'Noise Complaint',
                'description' => 'Complaints about excessive or disturbing noise from people, vehicles, businesses, or other sources.',
            ],
            [
                'name' => 'Domestic Dispute',
                'description' => 'Complaints involving conflicts, disputes, or disturbances between members of a household or family.',
            ],
            [
                'name' => 'Public Disturbance',
                'description' => 'Complaints about disruptive, disorderly, or inappropriate behavior occurring in public places.',
            ],
            [
                'name' => 'Illegal Parking',
                'description' => 'Complaints about vehicles blocking roads, sidewalks, entrances, or parking in prohibited areas.',
            ],
            [
                'name' => 'Garbage Disposal',
                'description' => 'Complaints about improper garbage disposal, uncollected waste, littering, or illegal dumping.',
            ],
            [
                'name' => 'Drainage Problem',
                'description' => 'Complaints about clogged, damaged, or inadequate drainage systems that may cause flooding or stagnant water.',
            ],
            [
                'name' => 'Road Damage',
                'description' => 'Complaints about damaged roads, potholes, cracks, or other road conditions that may affect public safety.',
            ],
            [
                'name' => 'Street Light Repair',
                'description' => 'Complaints about broken, malfunctioning, or insufficient street lighting in public areas.',
            ],
            [
                'name' => 'Water Supply Issue',
                'description' => 'Complaints about water interruptions, low water pressure, leaks, or other problems with the water supply.',
            ],
            [
                'name' => 'Electricity Concern',
                'description' => 'Complaints about electrical problems, damaged facilities, outages, or other electricity-related concerns.',
            ],
            [
                'name' => 'Stray Animals',
                'description' => 'Complaints about stray, roaming, aggressive, or potentially dangerous animals in the community.',
            ],
            [
                'name' => 'Illegal Construction',
                'description' => 'Complaints about construction activities that may lack required permits or violate local regulations.',
            ],
            [
                'name' => 'Environmental Concern',
                'description' => 'Complaints about activities or conditions that may negatively affect the environment or community surroundings.',
            ],
            [
                'name' => 'Business Permit Violation',
                'description' => 'Complaints about businesses operating without proper permits or violating applicable local regulations.',
            ],
            [
                'name' => 'Others',
                'description' => 'Complaints that do not fall under any of the available complaint categories.',
            ],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
