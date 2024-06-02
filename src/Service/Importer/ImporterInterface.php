<?php

declare(strict_types=1);

namespace App\Service\Importer;

interface ImporterInterface
{
    public function import($fileHandler): void;
}
