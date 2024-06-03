<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\LogRecord;
use App\Repository\LogRecordRepository;
use App\Service\Importer\LogImporter;
use App\Service\Reader\LogReader;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogImporterTest extends KernelTestCase
{
    /**
     * @var array<LogRecord> $store
     */
    private array $store = [];

    public function testImport(): void
    {
        self::bootKernel();
        $logger = $this->createMock(LoggerInterface::class);
        $importerService = new LogImporter(new LogReader(), $this->mockLoadEntityRepository(), $logger);
        $importerService->import($this->getFileHandler());

        $this->assertCount(20, $this->store);
        $this->assertContainsOnlyInstancesOf(LogRecord::class, $this->store);
        $this->assertSame('INVOICE-SERVICE', $this->store[2]->getServiceName());
        $this->assertSame(201, $this->store[2]->getStatusCode());
        $this->assertEquals(new \DateTimeImmutable('2018-08-18 10:26:53.0 +00:00'), $this->store[2]->getEventTime());
    }

    private function mockLoadEntityRepository(): LogRecordRepository
    {
        $repository = $this->createMock(LogRecordRepository::class);
        $repository->method('save')->willReturnCallback(function (LogRecord $logEntry) {
            $this->store[] = $logEntry;
        });
        $repository->method('flush')->willReturnCallback(function(){});
        $repository->method('getLastRecordTime')->willReturn(new \DateTimeImmutable('1970-01-01 00:00:00'));

        return $repository;
    }

    private function getFileHandler()
    {
        $fileName = dirname(__DIR__, 2) . '/filestorage/logs.log';
        return fopen($fileName, 'rb');
    }
}
