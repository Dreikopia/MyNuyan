<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HotlineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotline extends Model
{
    /** @use HasFactory<HotlineFactory> */
    use HasFactory;

    protected $guarded = [];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function category()
    {
        return $this->belongsTo(HotlineCategory::class, 'hotline_category_id');
    }

    public function numbers()
    {
        return $this->hasMany(HotlineNumber::class);
    }
}
