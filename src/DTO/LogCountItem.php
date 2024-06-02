<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class LogCountItem
{
    public function __construct(
        public int $count,
    )
    {
    }
}
