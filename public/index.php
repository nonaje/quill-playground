<?php

define('APPLICATION_INIT', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

/** @var \Quill\Contracts\ApplicationInterface $app */
$app = require_once __DIR__ . '/../boot/boot.php';

$request = $app->container->get(\Psr\Http\Message\ServerRequestInterface::class);

$app->processRequest($request);
