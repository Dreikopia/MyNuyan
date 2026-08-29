<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComplaintPriority;
use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => ComplaintStatus::class,
        'priority' => ComplaintPriority::class,
    ];

    protected $attributes = [
        'status' => ComplaintStatus::SUBMITTED->value,
        'priority' => ComplaintPriority::LOW->value,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }

    public function images()
    {
        return $this->hasMany(ComplaintImage::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ComplaintStatusHistory::class)
            ->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public static function statusCounts(User $user)
    {
        $counts = $user->complaints()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(ComplaintStatus::cases())
            ->mapWithKeys(fn ($status) => [
                $status->value => $counts->get($status->value, 0),
            ])
            ->put('all', $user->complaints()->count());
    }

    public static function allStatusCounts($categoryId = null, bool $archived = false)
    {
        $counts = static::query()
            ->when($archived, fn ($query) => $query->archived())
            ->when(! $archived, fn ($query) => $query->active())
            ->when(
                $categoryId,
                fn ($query, $categoryId) => $query->where('complaint_category_id', $categoryId)
            )
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(ComplaintStatus::cases())
            ->mapWithKeys(fn ($status) => [
                $status->value => $counts->get($status->value, 0),
            ])
            ->put('all', $counts->sum());
    }

    #[Override]
    protected static function booted(): void
    {

        static::creating(function (Complaint $complaint) {
            $complaint->complaint_id = self::generateComplaintId();
        });

        static::updating(function ($complaint) {
            if ($complaint->isDirty('status')) {
                if (in_array($complaint->status, [ComplaintStatus::RESOLVED, ComplaintStatus::REJECTED])) {
                    $complaint->is_archived = true;
                }
            }
        });
    }

    protected static function generateComplaintId(): string
    {
        $lastComplaint = self::latest('id')->first();

        if ($lastComplaint) {
            $lastNumber = (int) substr((string) $lastComplaint->complaint_id, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return '#'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
