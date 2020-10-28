<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Imgx\Fetcher;
use Pluf\Http\ServerRequestFactory;
use Pluf\Http\ResponseFactory;
use Pluf\Scion\ProcessTrackerInterface;

class FetcherTest extends TestCase
{

    /**
     *
     * @before
     */
    public function initTest()
    {
        $this->requestFactory = new ServerRequestFactory();
        $this->responseFactory = new ResponseFactory();
    }

    /**
     *
     * @test
     */
    public function provideParamsFileExisted()
    {
        $width = 300;
        $height = 500;
        $fit = 'cover';
        $position = 'top';
        $modulePath = __DIR__ . '/assets';
        $id = 1;
        $target = "$modulePath/$id"."_w$width-h$height-f$fit-p$position.jpeg";
        $request = $this->requestFactory->createServerRequest('GET', '/imgx/api/v2/cms/contents/1/content')// 
            ->withQueryParams([
                'w' => $width,
                'h' => $height,
                'f' => $fit,
                'p' => $position
            ]);
        // process tracker mock
        $processTracker = $this->createMock(ProcessTrackerInterface::class);
        $processTracker->expects($this->never())
            ->method('next')
            ->willReturn($target);

        $process = new Fetcher($modulePath, 'jpeg');
        $res = $process($request, $id, $processTracker);
        $this->assertEquals($target, $res);
    }
    
    /**
     *
     * @test
     */
    public function provideParamsFileDoesNotExisted()
    {
        $width = 350;
        $height = 500;
        $fit = 'cover';
        $position = 'top';
        $modulePath = __DIR__ . '/assets';
        $id = 1;
        $target = "$modulePath/$id\_w$width-h$height-f$fit-p$position.jpeg";
        $request = $this->requestFactory->createServerRequest('GET', '/imgx/api/v2/cms/contents/1/content')//
        ->withQueryParams([
            'w' => $width,
            'h' => $height,
            'f' => $fit,
            'p' => $position
        ]);
        // process tracker mock
        $processTracker = $this->createMock(ProcessTrackerInterface::class);
        $processTracker->expects($this->once())
        ->method('next')
        ->willReturn($target);
        
        $process = new Fetcher($modulePath, 'jpeg');
        $res = $process($request, $id, $processTracker);
        $this->assertEquals($target, $res);
    }
}

