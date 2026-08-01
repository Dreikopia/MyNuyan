<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ComplaintCategory::all();
        $users = User::all();

        foreach ($users as $user) {
            $user->complaints()->createMany(
                Complaint::factory()
                    ->count(rand(2, 5))
                    ->make()
                    ->map(function ($complaint) use ($categories) {
                        return [
                            'location' => $complaint->location,
                            'description' => $complaint->description,
                            'complaint_category_id' => $categories->random()->id,
                            'status' => $complaint->status,
                        ];
                    })
                    ->toArray()
            );
        }
    }
}
