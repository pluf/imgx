<?php
use Pluf\Di;
use Pluf\Http\StreamFactory;
use Pluf\Log\Logger;
use Pluf\Orm\EntityManagerFactoryBuilder;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\ObjectValidatorBuilder;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Atk4\Dsql\Connection;
use Atk4\Dsql\Debug;

/*
 * The container:
 *
 * This is a place to store list of services and load them if required.
 */
$container = new Di\Container();

/*
 * Logs
 * ********************************************************************************
 *
 * Services:
 *
 * - logger
 * ********************************************************************************
 */
$container->addService('logger', function () {
    $logger = Logger::getLogger('imgx');
    return $logger;
});

/*
 * IO
 * ********************************************************************************
 *
 * Services:
 *
 * - streamFactory
 * ********************************************************************************
 */
$container->addService('streamFactory', function () {
    $streamFactory = new StreamFactory();
    return $streamFactory;
});

/*
 * ORM (Entity manager)
 * ********************************************************************************
 *
 * Services:
 *
 * - entityManagerFactory
 * - dbConnection
 * - objectValidator
 * - objectMapperBuilder
 * - objectMapperJson
 * - objectMapperArray
 * - modelDescriptionRepository
 * ********************************************************************************
 */
$container->addService('entityManagerFactory', function ($dbConnection, $modelDescriptionRepository) {

    $schema = $_ENV['DB_SCHEMA'] ?? "sqlite";

    $builder = new ObjectMapperBuilder();
    $objectMapper = $builder->setType('array')
        ->setModelDescriptionRepository($modelDescriptionRepository)
        ->setSchema($schema)
        ->build();

    $builder = new EntityManagerFactoryBuilder();
    $factory = $builder->setConnection($dbConnection)
        ->setObjectMapper($objectMapper)
        ->setModelDescriptionRepository($modelDescriptionRepository)
        ->build();
    return $factory;
});

$container->addService('dbConnection', function () {
    $dsn = $_ENV['DB_DSN'] ?? "sqlite::memory:";
    $user = $_ENV['DB_USER'] ?? "root";
    $pass = $_ENV['DB_PASS'] ?? "";

    $c = Connection::connect($dsn, $user, $pass);

    $debug = $_ENV['DB_DEBUG'] ?? "false";
    if ($debug != 'false') {
        $c = new Debug\Stopwatch\Connection([
            'connection' => $c
        ]);
    }
    return $c;
});

$container->addService('objectValidator', function () {
    $builder = new ObjectValidatorBuilder();
    $objectValidator = $builder->build();
    return $objectValidator;
});

$container->addService('objectMapperBuilder', function ($modelDescriptionRepository) {
    $builder = new ObjectMapperBuilder();
    $builder->setModelDescriptionRepository($modelDescriptionRepository);
    return $builder;
});

$container->addService('objectMapperJson', function ($objectMapperBuilder) {
    $objectMapper = $objectMapperBuilder->addType('json')
        ->build();
    return $objectMapper;
});

$container->addService('objectMapperArray', function ($objectMapperBuilder) {
    $objectMapper = $objectMapperBuilder->addType('array')
        ->build();
    return $objectMapper;
});

$container->addService('modelDescriptionRepository', function () {
    $modelDescriptionRepository = new ModelDescriptionRepository([
        new ModelDescriptionLoaderAttribute()
    ]);
    return $modelDescriptionRepository;
});

/*
 * LM (License Manager)
 * ********************************************************************************
 *
 * Factories:
 *
 * - lmPrivetKey
 * - lmPublicKey
 * ********************************************************************************
 */

return $container;
