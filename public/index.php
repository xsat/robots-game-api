<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;

try {
    (new Application())->run();
} catch (Exception $exception) {
    echo get_class($exception), PHP_EOL;
    echo $exception->getMessage(), PHP_EOL;
    echo $exception->getTraceAsString();
}
