<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotlineCategory extends Model
{
    public function hotlines()
    {
        return $this->hasMany(Hotline::class);
    }
}
