<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Scion\UnitTracker;

class IntegrationTest extends TestCase
{

    public $unitTracker;

    /**
     *
     * @before
     */
    public function initTest()
    {
        // *****************************************************************
        // Loading unit tracker
        // *****************************************************************
        $units = include __DIR__ . '/../html/units.php';
        $container = include __DIR__ . '/../html/boot.php';
        $this->unitTracker = new UnitTracker($units, $container);
    }

    /**
     *
     * @test
     */
    public function mainTest()
    {
        // *****************************************************************
        // Processes
        // *****************************************************************
        $responseFactory = new ResponseFactory();
        $requestFactory = new ServerRequestFactory();
        $response = $this->unitTracker->doProcess([
            'request' => $requestFactory->createServerRequest('GET', '/imgx/api/v2/cms/contents/0/content'),
            'response' => $responseFactory->createResponse(200, 'Success'),
            'responseFactory' => $responseFactory
        ]);
        $this->assertNotNull($response);
    }

    /**
     *
     * @test
     */
    public function mainTestWithParam()
    {
        // *****************************************************************
        // Processes
        // *****************************************************************
        $responseFactory = new ResponseFactory();
        $requestFactory = new ServerRequestFactory();
        $response = $this->unitTracker->doProcess([
            'request' => $requestFactory->createServerRequest('GET', '/imgx/api/v2/cms/contents/0/content?w=100'),
            'response' => $responseFactory->createResponse(200, 'Success'),
            'responseFactory' => $responseFactory
        ]);
        $this->assertNotNull($response);
    }

    /**
     *
     * @test
     */
    public function urlTestWithParam()
    {
        // *****************************************************************
        // Processes
        // *****************************************************************
        $responseFactory = new ResponseFactory();
        $requestFactory = new ServerRequestFactory();
        $response = $this->unitTracker->doProcess([
            'request' => $requestFactory->createServerRequest('GET', '/imgx/https://cdn.viraweb123.ir/api/v2/cdn/libs/templates@0.1.0/images/300x600.jpg?w=100'),
            'response' => $responseFactory->createResponse(200, 'Success'),
            'responseFactory' => $responseFactory
        ]);
        $this->assertNotNull($response);
    }
}

