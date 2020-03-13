<?php

namespace JsonCsvConverter\Models;

/**
 * Class Result
 * @package JsonCsvConverter\Models
 */
class Result
{
    /**
     * @var string
     */
    private $downloadLink;

    /**
     * Result constructor.
     * @param string $downloadLink
     */
    public function __construct(string $downloadLink)
    {
        $this->downloadLink = $downloadLink;
    }

    /**
     * @return string
     */
    public function getDownloadLink(): string
    {
        return $this->downloadLink;
    }
}