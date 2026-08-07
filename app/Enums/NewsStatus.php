<?php

declare(strict_types=1);

namespace App\Enums;

enum NewsStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
        };
    }

    public static function values(): array
    {
        return array_map(fn (NewsStatus $status) => $status->value, self::cases());
    }
}
