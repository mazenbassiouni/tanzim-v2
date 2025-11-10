<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case DONE = 'done';
    case PENDING = 'pending';
    case ACTIVE = 'active';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'معلق',
            self::ACTIVE => 'جاري',
            self::DONE => 'منتهي',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
