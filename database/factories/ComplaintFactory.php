<?php

namespace Database\Factories;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition(): array
    {
        return [
            'complaint_category_id' => ComplaintCategory::inRandomOrder()->value('id'),
            'location' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement(
                array_map(
                    fn ($status) => $status->value,
                    ComplaintStatus::cases()
                )
            ),
        ];
    }
}
