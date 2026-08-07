<?php

namespace App\Models;

use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => NewsStatus::class,
    ];

    protected $attributes = [
        'status' => NewsStatus::DRAFT->value,
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }
}
