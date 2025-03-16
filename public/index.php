<?php

define('APPLICATION_INIT', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

/** @var \Quill\Contracts\ApplicationInterface $app */
$app = require_once __DIR__ . '/../boot/boot.php';

/** @var \Nyholm\Psr7Server\ServerRequestCreatorInterface $request */
$request = $app->container->get(\Nyholm\Psr7Server\ServerRequestCreatorInterface::class);

$app->processRequest($request->fromGlobals());