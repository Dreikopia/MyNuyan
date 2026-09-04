<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotlineNumber extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function hotline()
    {
        return $this->belongsTo(Hotline::class);
    }
}
