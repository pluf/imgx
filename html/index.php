<?php
require __DIR__ . '/../vendor/autoload.php';

use Pluf\Options;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Repository;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Data\Schema\MySQLSchema;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf\Db\Connection;
use Pluf\Di\Container;
use Pluf\Http\HttpResponseEmitter;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Imgx\Content;
use Pluf\Scion\UnitTracker;

// *****************************************************************
// Services
// TODO: maso, 2020: we are in need to discover and load services the
// springBoot is a very good model.
// *****************************************************************
$container = new Container();

$container['configs'] = Container::service(function () {
    return new Options(require __DIR__ . '/configs.php');
});

$container['connection'] = Container::service(function (Options $configs) {
    $dbOptions = $configs->startsWith('db_', true);
    return Connection::connect($dbOptions->dsn, $dbOptions->user, $dbOptions->password);
});

$container['schema'] = Container::service(function (Options $configs) {
    $schemaName = $configs->data_schema;
    switch ($schemaName) {
        case 'sqlite':
            return new SQLiteSchema();
        case 'mysql':
            return new MySQLSchema();
        default:
            throw new Exception('Unsupported data schema');
    }
});

$container['modelDescriptionRepository'] = Container::service(function () {
    return new ModelDescriptionRepository([
        // TODO: maso, 2020: we need a model description loader with reflection.
        new MapModelDescriptionLoader([
            Content::class => require __DIR__ . '/../md/ContentMd.php'
        ])
    ]);
});

$container['contentRepository'] = Container::service(function ($connection, $schema, $modelDescriptionRepository) {
    return Repository::getInstance([
        'model' => Content::class,
        'connection' => $connection,
        'schema' => $schema,
        'mdr' => $modelDescriptionRepository
    ]);
});

// *****************************************************************
// Processes
// *****************************************************************
$unitTracker = new UnitTracker(require __DIR__ . '/units.php', $container);
$responseFactory = new ResponseFactory();
$httpResponseEmitter = new HttpResponseEmitter();
$httpResponseEmitter->emit($unitTracker->doProcess([
    'request' => ServerRequestFactory::createFromGlobals(),
    'response' => $responseFactory->createResponse(200, 'Success'),
    'responseFactory' => $responseFactory
]));


