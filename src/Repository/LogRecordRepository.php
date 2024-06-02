<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\LogStatsApiRequest;
use App\Entity\LogRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LogRecord>
 */
class LogRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogRecord::class);
    }


    public function save(LogRecord $logRecord): void
    {
        $this->getEntityManager()->persist($logRecord);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function getLastRecordTime(): \DateTimeImmutable
    {
        /** @var LogRecord $lastRecord */
        $lastRecord = $this->createQueryBuilder('log')
            ->orderBy('log.eventTime', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();

        if (null === $lastRecord) {
            // Fallback for the first run.
            return new \DateTimeImmutable('1970-01-01 00:00:00');
        }

        return $lastRecord->getEventTime();
    }

    public function getLogsCount(LogStatsApiRequest $apiRequest): int
    {
        $queryBuilder = $this->createQueryBuilder('log');

        if ($apiRequest->serviceNames) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('log.serviceName', ':serviceNames'))
                ->setParameter('serviceNames', $apiRequest->serviceNames);
        }

        if ($apiRequest->statusCode) {
            $queryBuilder->andWhere('log.statusCode = :statusCode')
                ->setParameter('statusCode', $apiRequest->statusCode);
        }

        if ($apiRequest->startDate) {
            $queryBuilder->andWhere('log.eventTime >= :startDate')
                ->setParameter('startDate', new \DateTime($apiRequest->startDate));
        }

        if ($apiRequest->endDate) {
            $queryBuilder->andWhere('log.eventTime <= :endDate')
                ->setParameter('endDate', new \DateTime($apiRequest->endDate));
        }

        return $queryBuilder->select('COUNT(log.id)')->getQuery()->getSingleScalarResult();
    }
}
