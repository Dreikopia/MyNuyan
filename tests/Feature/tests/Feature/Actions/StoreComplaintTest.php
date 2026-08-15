<?php

declare(strict_types=1);

use App\Actions\StoreComplaint;
use App\Enums\ComplaintStatus;
use App\Models\ComplaintCategory;
use App\Models\ComplaintImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('creates a complaint with a submitted status history', function () {
    $user = User::factory()->create();
    $category = ComplaintCategory::factory()->create();

    $action = new StoreComplaint($user);

    $action->handle([
        'location' => 'Purok 3',
        'description' => 'Streetlight is broken',
        'complaint_category_id' => (string) $category->id,
        'images' => [],
    ]);

    expect($user->complaints()->count())->toBe(1);

    $complaint = $user->complaints()->first();

    expect($complaint->location)->toBe('Purok 3');
    expect($complaint->description)->toBe('Streetlight is broken');
    expect($complaint->status)->toBe(ComplaintStatus::SUBMITTED);
    expect($complaint->statusHistories()->count())->toBe(1);
});

it('stores uploaded images and links them to the complaint', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = ComplaintCategory::factory()->create();

    $action = new StoreComplaint($user);

    $image = UploadedFile::fake()->image('pothole.jpg');

    $action->handle([
        'location' => 'Purok 5',
        'description' => 'Large pothole on main road',
        'complaint_category_id' => (string) $category->id,
        'images' => [$image],
    ]);

    $complaint = $user->complaints()->first();
    expect($complaint->images()->count())->toBe(1);

    $storedPath = $complaint->images()->first()->image_path;
    Storage::disk('public')->Exists($storedPath);
    Storage::disk('public')->Exists($storedPath);
});

it('cleans up uploaded images when the transaction fails after storing them', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $category = ComplaintCategory::factory()->create();
    $action = new StoreComplaint($user);
    $image = UploadedFile::fake()->image('pothole.jpg');

    ComplaintImage::creating(function () {
        throw new RuntimeException('database failure');
    });

    expect(fn () => $action->handle([
        'location' => 'Purok 7',
        'description' => 'Drainage issue',
        'complaint_category_id' => (string) $category->id,
        'images' => [$image],
    ]))->toThrow(RuntimeException::class, 'database failure');

    expect(Storage::disk('public')->allFiles('complaints'))->toBe([]);
    expect($user->complaints()->count())->toBe(0);
});

it('creates a complaint with no images without error', function () {
    $user = User::factory()->create();
    $category = ComplaintCategory::factory()->create();

    $action = new StoreComplaint($user);

    $action->handle([
        'location' => 'Purok 1',
        'description' => 'Noise complaint',
        'complaint_category_id' => (string) $category->id,
    ]);

    expect($user->complaints()->count())->toBe(1);
    expect($user->complaints()->first()->images()->count())->toBe(0);
});
