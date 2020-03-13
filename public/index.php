<?php

require __DIR__.'/../vendor/autoload.php';

$app = new \JsonCsvConverter\JsonCsvConverter(__DIR__);

$app->handleJson();

