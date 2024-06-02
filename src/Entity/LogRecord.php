<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LogRecordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRecordRepository::class)]
#[ORM\Index(name: 'status', fields: ['statusCode'])]
#[ORM\Index(name: 'service', fields: ['serviceName'])]
#[ORM\Index(name: 'date', fields: ['eventTime'])]
class LogRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)]
    private int $id;

    #[ORM\Column(length: 32, nullable: false)]
    private string $serviceName;

    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    private int $statusCode;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $eventTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): static
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getEventTime(): \DateTimeImmutable
    {
        return $this->eventTime;
    }

    public function setEventTime(\DateTimeImmutable $eventTime): static
    {
        $this->eventTime = $eventTime;

        return $this;
    }
}
