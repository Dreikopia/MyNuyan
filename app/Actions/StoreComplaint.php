<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ComplaintStatus;
use App\Models\ComplaintImage;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreComplaint
{
    public function __construct(#[CurrentUser] protected User $user)
    {
        //
    }

    public function handle(array $attributes): void
    {
        $data = collect($attributes)->only([
            'location',
            'description',
            'complaint_category_id',
        ])->toArray();

        DB::transaction(function () use ($data, $attributes) {
            $complaint = $this->user->complaints()->create($data);

            $complaint->statusHistories()->create([
                'status' => ComplaintStatus::SUBMITTED,
                'changed_by' => null,
            ]);

            $storedPaths = [];

            try {
                foreach ($attributes['images'] ?? [] as $image) {
                    $path = $image->store('complaints', 'public');
                    $storedPaths[] = $path;

                    ComplaintImage::create([
                        'complaint_id' => $complaint->id,
                        'image_path' => $path,
                    ]);
                }
            } catch (Throwable $e) {
                foreach ($storedPaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }

                throw $e;
            }
        });
    }
}
