<?php declare(strict_types=1);

namespace App\Module\File\Enum;

enum FileStatusEnum: string
{
    case UNPROCESSED = 'UNPROCESSED';
    case CHECKED = 'CHECKED';
    case BLOCKED = 'BLOCKED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
