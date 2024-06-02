<?php

declare(strict_types=1);

namespace App\Service\Reader;

class LogReader implements ReaderInterface
{
//        private int $chunkSize = 1024 * 1024; // 1MB
    private int $chunkSize = 200;

    // Placeholder for partial lines.
    private string $suffix = '';

    private string $bom = "\xEF\xBB\xBF";

    public function read($fileHandler, int $position): \Generator
    {
        // Read the file backwards on chunks.
        $chunkSize = $this->chunkSize;
        while (0 < $position) {
            $position -= $chunkSize;

            if (0 > $position) {
                // We've reached the beginning of the file, so make sure to only read the meaningful content.
                $chunkSize = $this->chunkSize + $position;
                $position = 0;
            }
            fseek($fileHandler, $position);

            yield from $this->getLines(fread($fileHandler, $chunkSize), $position);
        }
    }

    private function getLines(string $chunk, int $position): \Generator
    {
        // Append any partial line remaining from the previous chunk and then split the lines.
        $lines = explode("\n", $chunk . $this->suffix);
        if (0 === count($lines)) {
            throw new \RuntimeException('Error during reading');
        }

        if (0 < $position) {
            // Handle partial lines.
            $this->suffix = array_shift($lines);
        }

        for ($index = max(array_keys($lines)); $index >= 0; $index--) {
            // Check for the BOM existence and remove it if needed.
            if (str_starts_with($lines[$index], $this->bom)) {
                $lines[$index] = ltrim($lines[$index], $this->bom);
            }

            yield rtrim($lines[$index], "\r");
        }
    }
}
