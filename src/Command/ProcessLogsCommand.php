<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Importer\LogImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:process_logs', description: 'Parses log file and records it into the DB')]
final class ProcessLogsCommand extends Command
{
    // This could be easily exported to an environment variable.
    private string $filePath = '/filestorage/logs.log';

    public function __construct(private readonly string $projectDir, private readonly LogImporter $logImporter)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Check if file exists and can be opened.
        $file = $this->projectDir . $this->filePath;
        if (!file_exists($file) || false === ($fh = fopen($file, 'rb'))) {
            $output->writeln('Could not open the file.');

            return Command::FAILURE;
        }

        try {
            $this->logImporter->import($fh);
        } catch (\Throwable $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        } finally {
            fclose($fh);
        }

        return Command::SUCCESS;
    }

}
