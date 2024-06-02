<?php

declare(strict_types=1);

namespace App\Enum;

enum ServiceName: string
{
    case USER = 'USER-SERVICE';
    case INVOICE = 'INVOICE-SERVICE';

    public static function getReadableNames(): array
    {
        return array_column(self::cases(), 'value');
    }
}
