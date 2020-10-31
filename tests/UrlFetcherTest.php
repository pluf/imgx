<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Imgx\UrlFetcher;
use Pluf\Scion\ProcessTrackerInterface;

class UrlFetcherTest extends TestCase
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
        $url = 'https://cdn.viraweb123.ir/api/v2/cdn/libs/templates@0.1.0/images/800x800.jpg';
        $hash = md5($url); // fdc6a552871faff5ff74b54876ad4dbb
        $target = "$modulePath/$hash" . "_w$width-h$height-f$fit-p$position.jpeg";
        $request = $this->requestFactory->createServerRequest('GET', "/imgx/$url")-> //
        withQueryParams([
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

        $process = new UrlFetcher($modulePath, 'jpeg');
        $res = $process($request, $url, $processTracker);
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
        $url = 'https://cdn.viraweb123.ir/api/v2/cdn/libs/templates@0.1.0/images/300x600.jpg';
        $hash = md5($url); // 4cd5a730e090befd4a72838e6705ff87
        $target = "$modulePath/$hash\_w$width-h$height-f$fit-p$position.jpeg";
        $request = $this->requestFactory->createServerRequest('GET', "/imgx/$url")-> //
        withQueryParams([
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

        $process = new UrlFetcher($modulePath, 'jpeg');
        $res = $process($request, $url, $processTracker);
        $this->assertEquals($target, $res);
    }
}

