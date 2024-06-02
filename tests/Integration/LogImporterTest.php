<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\LogRecord;
use App\Repository\LogRecordRepository;
use App\Service\Importer\LogImporter;
use App\Service\Reader\LogReader;
use PhpCsFixer\Cache\FileHandler;
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
        // Initialize importer
        $logger = $this->createMock(LoggerInterface::class);
        $reader = $this->createMock(LogReader::class);
        $importerService = new LogImporter($reader, $this->mockLoadEntityRepository(), $logger);
        $importerService->import($this->getFileHandler());

        dd($this->store);

        // Assertions
    }

    private function mockLoadEntityRepository(): LogRecordRepository
    {
        $repository = $this->createMock(LogRecordRepository::class);
        $repository->method('save')->willReturnCallback(function (LogRecord $logEntry) {
            $this->store[] = $logEntry;
        });
        $repository->method('flush')->willReturnCallback(function(){});

        return $repository;
    }

    private function getFileHandler()
    {
        $fileName = dirname(__DIR__, 2) . '/filestorage/logs.log';
        return fopen($fileName, 'rb');
    }
}
