<?php

define('APPLICATION_INIT', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

/** @var \Quill\Contracts\ApplicationInterface $app */
$app = require_once __DIR__ . '/../boot/boot.php';

$app->process(\Quill\Factory\Psr7\Psr7Factory::createPsr7ServerRequest());
