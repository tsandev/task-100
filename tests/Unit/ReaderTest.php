<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Service\Reader\LogReader;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    public function testReader(): void
    {
        $reader = new LogReader();

        $fh = fopen(dirname(__DIR__, 2) . '/filestorage/logs.log', 'rb');
        fseek($fh, 0, SEEK_END);
        $position = ftell($fh);

        $rows = [];
        foreach ($reader->read($fh, $position) as $line) {
            $rows[] = $line;
        }

        // Assert total count of items.
        $this->assertCount(20, $rows);

        // Assert correct order of reading.
        $this->assertSame(
            'INVOICE-SERVICE - - [18/Aug/2018:10:26:53 +0000] "POST /invoices HTTP/1.1" 201',
            $rows[2]
        );
        $this->assertSame(
            'USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201',
            $rows[19],
        );
    }
}
