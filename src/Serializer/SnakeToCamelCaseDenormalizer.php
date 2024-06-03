<?php

declare(strict_types=1);

namespace App\Serializer;

use App\DTO\LogStatsApiRequest;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class SnakeToCamelCaseDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): LogStatsApiRequest
    {
        // Create a new instance of the DTO and set its properties by converting the snake_case to camelCase if needed.
        $dto = new LogStatsApiRequest();
        foreach ($data as $key => $value) {
            $camelCaseKey = lcfirst(str_replace('_', '', ucwords($key, '_')));
            if (!property_exists(LogStatsApiRequest::class, $camelCaseKey)) {
                // Skip any arguments which don't fit the DTO.
                continue;
            }

            $value = match ($camelCaseKey) {
                'statusCode' => (int)$value,
                'startDate', 'endDate' => (string)$value,
                'serviceNames' => array_map('trim', $value),
                default => throw new \InvalidArgumentException(
                    sprintf('Provided argument %s is invalid', $camelCaseKey),
                ),
            };

            $dto->{$camelCaseKey} = $value;
        }

        return $dto;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === LogStatsApiRequest::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [LogStatsApiRequest::class => true];
    }
}
