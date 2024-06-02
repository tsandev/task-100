<?php

declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LogCountItem')]
final readonly class LogCountItem
{
    public function __construct(
        #[OA\Property(title: 'count', format: 'int64')]
        public int $count,
    )
    {
    }
}
