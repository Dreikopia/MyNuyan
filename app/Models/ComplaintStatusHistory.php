<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Model;

class ComplaintStatusHistory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => ComplaintStatus::class,
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }
}
