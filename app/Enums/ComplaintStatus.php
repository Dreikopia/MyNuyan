<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case IN_PROGRESS = 'in_progress';
    case PENDING_CONFIRMATION = 'pending_confirmation';
    case RESOLVED = 'resolved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED => 'Submitted',
            self::UNDER_REVIEW => 'Under Review',
            self::IN_PROGRESS => 'In Progress',
            self::PENDING_CONFIRMATION => 'Pending Confirmation',
            self::RESOLVED => 'Resolved',
            self::REJECTED => 'Rejected',
        };
    }

    public static function values(): array
    {
        return array_map(fn (ComplaintStatus $status) => $status->value, self::cases());
    }
}
