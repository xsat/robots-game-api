<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'on');

use App\Application;

try {
    (new Application())->run();
} catch (Exception $exception) {
    echo $exception->getMessage(), PHP_EOL;
    echo $exception->getTraceAsString();
}
