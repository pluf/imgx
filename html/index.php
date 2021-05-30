<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pluf\Di\Container;
use Pluf\Http\HttpResponseEmitter;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Scion\UnitTracker;

// *****************************************************************
// Loading unit tracker
// *****************************************************************
$units = include __DIR__ . '/units.php';;
$container = include __DIR__ . '/boot.php';
$unitTracker = new UnitTracker($units, $container);


// *****************************************************************
// Process the input request with UnitManager
// *****************************************************************
$responseFactory = new ResponseFactory();
$httpResponseEmitter = new HttpResponseEmitter();
$response = $unitTracker->doProcess([
    'request' => ServerRequestFactory::createFromGlobals(),
    'response' => $responseFactory->createResponse(200, 'Success'),
    'responseFactory' => $responseFactory
]);
$httpResponseEmitter->emit($response);

