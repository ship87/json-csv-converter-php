<?php

namespace JsonCsvConverter\Models;

use Dotenv\Dotenv;

/**
 * Class Config
 * @package JsonCsvConverter\Models
 */
class Config
{
    /**
     * @var string
     */
    private $directoryDownload;

    /**
     * @var string
     */
    private $patternFileName;

    /**
     * @var string
     */
    private $appUrl;

    /**
     * @var string
     */
    private $appPort;

    /**
     * @var array
     */
    private const ENV_VARIABLES = [
        'DirectoryDownload' => 'JSON_CSV_CONVERTER_PHP_DIRECTORY_DOWNLOAD',
        'PatternFileName' => 'JSON_CSV_CONVERTER_PHP_PATTERN_FILE_NAME',
        'AppUrl' => 'JSON_CSV_CONVERTER_PHP_APP_URL',
        'AppPort' => 'JSON_CSV_CONVERTER_PHP_APP_PORT',
    ];

    /**
     * Config constructor.
     * @param string $dir
     */
    public function __construct(string $dir)
    {
        $this->initConfig($dir);
    }

    /**
     * @return string
     */
    public function getDirectoryDownload(): string
    {
        return $this->directoryDownload;
    }

    /**
     * @param string $directoryDownload
     */
    public function setDirectoryDownload(string $directoryDownload): void
    {
        $this->directoryDownload = $directoryDownload;
    }

    /**
     * @return string
     */
    public function getPatternFileName(): string
    {
        return $this->patternFileName;
    }

    /**
     * @param string $patternFileName
     */
    public function setPatternFileName(string $patternFileName): void
    {
        $this->patternFileName = $patternFileName;
    }

    /**
     * @return string
     */
    public function getAppUrl(): string
    {
        return $this->appUrl;
    }

    /**
     * @param string $appUrl
     */
    public function setAppUrl(string $appUrl): void
    {
        $this->appUrl = $appUrl;
    }

    /**
     * @return string
     */
    public function getAppPort(): string
    {
        return $this->appPort;
    }

    /**
     * @param string $appPort
     */
    public function setAppPort(string $appPort): void
    {
        $this->appPort = $appPort;
    }

    /**
     * @param string $dir
     */
    private function initConfig(string $dir)
    {
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->load();
        $this->setEnvVariables();
    }

    private function setEnvVariables(): void
    {
        foreach (self::ENV_VARIABLES as $variable => $value) {
            $this->{'set' . $variable} (getenv($value));
        }
    }
}