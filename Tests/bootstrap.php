<?php

$path = __DIR__.'/../vendor/autoload.php';
if (!is_file($path)) {
    throw new RuntimeException('There is missing autoload.php file.');
}

require_once $path;
