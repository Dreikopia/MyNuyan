<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
