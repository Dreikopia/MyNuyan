<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComplaintPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'default_priority' => ComplaintPriority::class,
    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
