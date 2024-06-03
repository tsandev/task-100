<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LogStatsApiRequest
{
    public function __construct(
        #[Assert\NotBlank(allowNull: true)]
        public array|string|null $serviceNames = null,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Range(
            notInRangeMessage: "Status code should be in the range {{ min }} - {{ max }}.", min: 100, max: 511
        )]
        public ?int $statusCode = null,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\DateTime(message: 'Wrong format of the startDate. Accepted format is Y-m-d H:i:s')]
        public ?string $startDate = null, // Using only the default format, which can be extended if needed.

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\DateTime(message: 'Wrong format of the endDate. Accepted format is Y-m-d H:i:s')]
        public ?string $endDate = null // Using only the default format, which can be extended if needed.
    )
    {
    }
}
