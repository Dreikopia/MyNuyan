<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComplaintPriority;
use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function allStatusCounts($categoryId = null)
    {
        $counts = static::query()
            ->when($categoryId, fn ($query, $categoryId) => $query->where('complaint_category_id', $categoryId))
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(ComplaintStatus::cases())
            ->mapWithKeys(fn ($status) => [
                $status->value => $counts->get($status->value, 0),
            ])
            ->put('all', $counts->sum());
    }

    // app/Models/Complaint.php

    protected static function booted(): void
    {
        static::creating(function (Complaint $complaint) {
            $complaint->complaint_id = self::generateComplaintId();
        });
    }

    protected static function generateComplaintId(): string
    {
        $lastComplaint = self::latest('id')->first();

        if ($lastComplaint) {
            $lastNumber = (int) substr($lastComplaint->complaint_id, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'MYN-'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
