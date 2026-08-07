<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => ComplaintStatus::class,
    ];

    protected $attributes = [
        'status' => ComplaintStatus::SUBMITTED->value,
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
}
