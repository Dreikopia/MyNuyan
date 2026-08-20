<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\NewsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = NewsCategory::all();
        $admins = Admin::all();

        foreach ($admins as $admin) {
            $newsCount = rand(2, 8);

            for ($i = 0; $i < $newsCount; $i++) {

                $imagePath = null;

                // 70% chance of having an image
                if (fake()->boolean(70)) {

                    $imageUrl = 'https://picsum.photos/800/500?random='.fake()->unique()->numberBetween(1, 10000);

                    $response = Http::get($imageUrl);

                    if ($response->successful()) {
                        $filename = 'news/'.fake()->uuid().'.jpg';

                        Storage::disk('public')->put(
                            $filename,
                            $response->body()
                        );

                        $imagePath = $filename;
                    }
                }

                $admin->news()->create([
                    'title' => fake()->sentence(),
                    'description' => fake()->paragraph(),
                    'image_path' => $imagePath,
                    'news_category_id' => $categories->random()->id,
                    'status' => fake()->randomElement([
                        'draft',
                        'published',
                    ]),
                ]);
            }
        }
    }
}
