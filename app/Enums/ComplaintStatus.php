<?php

declare(strict_types=1);

namespace App\Enums;

enum ComplaintStatus: string
{
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case REJECTED = 'rejected';

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::SUBMITTED => [self::UNDER_REVIEW, self::REJECTED],
            self::UNDER_REVIEW => [self::IN_PROGRESS, self::REJECTED],
            self::IN_PROGRESS => [self::RESOLVED],
            self::RESOLVED, self::REJECTED => [], // end states, nothing after
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED => 'Submitted',
            self::UNDER_REVIEW => 'Under Review',
            self::IN_PROGRESS => 'In Progress',
            self::RESOLVED => 'Resolved',
            self::REJECTED => 'Rejected',
        };
    }

    public function canTransitionTo(ComplaintStatus $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    public static function values(): array
    {
        return array_map(fn (ComplaintStatus $status) => $status->value, self::cases());
    }
}
