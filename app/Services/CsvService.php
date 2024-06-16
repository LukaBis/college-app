<?php

namespace App\Services;

class CsvService
{
    protected $csvContents;

    protected $headers;

    protected $data;

    public function __construct(string $csvContents)
    {
        $this->csvContents = $csvContents;
        $this->parseCsv();
    }

    /**
     * Parse the CSV contents.
     */
    protected function parseCsv(): void
    {
        $lines = explode(PHP_EOL, $this->csvContents);
        $this->headers = str_getcsv(array_shift($lines)); // Get the first line as headers
        $this->data = array_map('str_getcsv', $lines); // Parse remaining lines as data
    }

    /**
     * Validate the CSV headers.
     *
     * @throws \Exception
     */
    public function validate(): void
    {
        $requiredHeaders = config('students.csv-attributes');

        foreach ($requiredHeaders as $header) {
            if (! in_array($header, $this->headers)) {
                throw new \Exception("CSV file is invalid. Missing required header: $header.");
            }
        }
    }

    /**
     * Get items from the CSV.
     */
    public function getItems(): array
    {
        $items = [];
        $headerMap = config('students.csv-attributes');
        $headerIndexes = array_flip($this->headers);

        foreach ($this->data as $row) {
            if (count($row) < count($this->headers)) {
                continue; // Skip rows that don't have enough columns
            }

            $item = [];
            foreach ($headerMap as $key => $header) {
                if (isset($headerIndexes[$header])) {
                    $item[$key] = $row[$headerIndexes[$header]];
                }
            }

            if (! empty($item)) {
                $items[] = $item;
            }
        }

        return $items;
    }
}
