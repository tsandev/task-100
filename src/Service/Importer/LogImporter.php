<?php

declare(strict_types=1);

namespace App\Service\Importer;

use App\Entity\LogRecord;
use App\Repository\LogRecordRepository;
use App\Service\Reader\ReaderInterface;
use Psr\Log\LoggerInterface;

final class LogImporter implements ImporterInterface
{
    private int $batchSize = 500;

    private \DateTimeInterface $lastImportRecord;

    public function __construct(
        private readonly ReaderInterface $reader,
        private readonly LogRecordRepository $logRecordRepository,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function import($fileHandler): void
    {
        $this->lastImportRecord = $this->logRecordRepository->getLastRecordTime();

        // Move the file pointer to the end of the file. We'll read the file backwards.
        fseek($fileHandler, 0, SEEK_END);
        // Get the position from the pointer.
        $position = ftell($fileHandler);

        foreach ($this->reader->read($fileHandler, $position) as $index => $line) {
            // If the script reached the previous imported item, stop.
            if (false === $this->createLogRecord($line)) {
                break;
            }

            if ($index % $this->batchSize === 0) {
                $this->logRecordRepository->flush();
            }
        }

        // Make sure to flush any leftovers if needed.
        $this->logRecordRepository->flush();
    }

    /**
     * @throws \Throwable
     */
    private function createLogRecord($line): bool
    {
        // Define the regular expression pattern
        $pattern = '/^([\w-]+) - - \[(.*?)\] "(.*?)" (\d{3})$/';
        // Use preg_match to extract the components
        if (false === preg_match($pattern, $line, $matches)) {
            throw new \RuntimeException(sprintf('Line % cannot be decoded', $line));
        }

        try {
            $serviceName = $matches[1];
            $time = new \DateTimeImmutable($matches[2]);
            $requestCode = is_numeric($matches[4])
                ? (int)$matches[4]
                : throw new \InvalidArgumentException('Wrong status code');

            if ($time <= $this->lastImportRecord) {
                // Current log has been already added in the DB, no need to continue.
                return false;
            }

            $log = new LogRecord();
            $log->setEventTime($time)
                ->setStatusCode($requestCode)
                ->setServiceName($serviceName);
            $this->logRecordRepository->save($log);

            return true;
        } catch (\Throwable $exception) {
            $this->logger->critical(sprintf('Error while creating the DB record: %s', $exception->getMessage()));

            throw $exception;
        }
    }

}
