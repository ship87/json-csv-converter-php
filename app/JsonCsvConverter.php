<?php

namespace JsonCsvConverter;

use Exception;
use JsonCsvConverter\Models\Config;
use JsonCsvConverter\Models\Line;
use JsonCsvConverter\Models\Result;

/**
 * Class JsonCsvConverter
 * @package JsonCsvConverter
 */
class JsonCsvConverter
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $uniqueFileName;

    /**
     * @var string
     */
    private $uniqueFilenameWithPath;

    /**
     * @var Config
     */
    private $config;

    /**
     * JsonCsvConverter constructor.
     * @param $dir
     */
    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->config = new Config($dir . '/../');
    }

    /**
     * @throws Exception
     */
    public function handleJson()
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $this->showNotFound();
        }

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if ($requestMethod !== 'POST') {
            $this->showNotAllowedMethod($requestMethod);
        }

        $contentType = $_SERVER['CONTENT_TYPE'];
        if ($contentType !== 'application/json') {
            $this->showUnsupportedMediaType($contentType);
        }

        $startTime = microtime(true);

        $inputJSON = file_get_contents('php://input');
        $this->setUniqueFileNameWithPath();

        try {
            $this->jsonToCSV($inputJSON);

            $downloadLink = $this->config->getAppUrl() . DIRECTORY_SEPARATOR .
                $this->config->getDirectoryDownload() . DIRECTORY_SEPARATOR .
                $this->uniqueFileName;
            $result = new Result($downloadLink);
            $endTime = microtime(true);
            $elapsedTimeInMs = ($endTime - $startTime) * 1000000;
            $usagedMemory = $this->bToKb(memory_get_usage(true));

            $this->showResult($elapsedTimeInMs, $usagedMemory, $result);
        } catch (Exception $e) {
            $this->showServerError($e->getMessage());
        }

        return true;
    }

    /**
     * @param float $elapsedTimeInMs
     * @param float $usagedMemory
     * @param Result $result
     */
    private function showResult(float $elapsedTimeInMs, float $usagedMemory, Result $result)
    {
        header('Content-Type: application/json');
        header('X-Elapsed-Time: ' . $elapsedTimeInMs . ' ms');
        header('X-Usaged-Memory: ' . $usagedMemory . ' KiB');
        echo json_encode([
            'download_link' => $result->getDownloadLink()
        ]);
    }

    /**
     * @param string $message
     */
    private function showServerError(string $message)
    {
        error_log($message, 0);
        ob_end_clean();
        header('HTTP/1.1 500 Internal Server Error');
        echo $message;
        exit;
    }

    private function showNotFound(): void
    {
        ob_end_clean();
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    private function showNotAllowedMethod($method): void
    {
        ob_end_clean();
        header('HTTP/1.1 405 Method Not Allowed');
        echo 'Method ' . $method . ' not allowed';
        exit;
    }

    private function showUnsupportedMediaType($contentType): void
    {
        ob_end_clean();
        header('HTTP/1.1 415 Unsupported Media Type');
        echo 'Media type ' . $contentType . ' not supported';
        exit;
    }

    /**
     * @return array
     */
    private function setUniqueFileNameWithPath()
    {
        do {
            $filename = str_replace('*', uniqid(), $this->config->getPatternFileName());
            $filenameWithPath = $this->dir . DIRECTORY_SEPARATOR .
                $this->config->getDirectoryDownload() . DIRECTORY_SEPARATOR .
                $filename;
            $files = glob($filenameWithPath);
        } while (count($files) > 0);


        $this->uniqueFileName = $filename;
        $this->uniqueFilenameWithPath = $filenameWithPath;
    }

    /**
     * @param $json
     * @param $filenameWithPath
     * @throws Exception
     */
    private function jsonToCSV($json)
    {
        if (empty($json)) {
            throw new Exception('Empty json');
        }

        $decodedData = json_decode($json, true);
        if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Cannot decode json');
        }

        $data = [];
        foreach ($decodedData as $item) {

            if (empty($item['number'])) {
                throw new Exception('Json item have not field - number');
            }
            if (empty($item['columns'])) {
                throw new Exception('Json item have not field - columns');
            }

            $number = $item['number'];
            $columns = $item['columns'];

            $data[] = new Line($number, $columns);
        }

        usort($data, function (Line $firstItem, Line $secondItem) {
            return $firstItem->getNumber() > $secondItem->getNumber();
        });

        $directory = $this->dir . DIRECTORY_SEPARATOR . $this->config->getDirectoryDownload();

        try {
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
        } catch (Exception $e) {
            throw new Exception('Cannot create directory');
        }

        $fp = fopen($this->uniqueFilenameWithPath, 'w');

        if (!$fp) {
            throw new Exception('Unable to open file');
        }

        if (empty($data)) {
            throw new Exception('Not valid json');
        }

        foreach ($data as $item) {
            /** @var $item Line */
            fputcsv($fp, $item->getColumns());
        }
        fclose($fp);
    }

    /**
     * @param int $size
     * @return int
     */
    private function bToKb(int $size)
    {
        return round($size / 1024, 0);
    }
}