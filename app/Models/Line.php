<?php

namespace JsonCsvConverter\Models;

/**
 * Class Line
 * @package JsonCsvConverter\Models
 */
class Line
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var array
     */
    private $columns;

    /**
     * Result constructor.
     * @param string $donwnloadLink
     */
    public function __construct(int $number, array $columns)
    {
        $this->number = $number;
        $this->columns = $columns;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}