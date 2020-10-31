<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Imgx\UrlDownloader;
use Pluf\Scion\UnitTrackerInterface;

class UrlDownloaderTest extends TestCase
{

    public static $repository;

    /**
     *
     * @beforClass
     */
    public static function setUpBeforeClass(): void
    {}

    /**
     *
     * @test
     */
    public function existedOriginTest()
    {
        $origin = __DIR__ . '/assets/sample-1.jpeg';

        // process tracker mock
        $processTracker = $this->createMock(UnitTrackerInterface::class);
        $processTracker->expects($this->once())
            ->method('next')
            ->willReturn($origin);
        $process = new UrlDownloader();
        $res = $process('', $origin, $processTracker);
        $this->assertEquals($res, $origin);
    }

    /**
     *
     * @test
     */
    public function nonexistedOriginTest()
    {
        $origin = '/tmp/test-assets-sample-' . rand();
        $url = 'https://cdn.viraweb123.ir/api/v2/cdn/libs/templates@0.1.0/images/300x600.jpg';

        // process tracker mock
        $processTracker = $this->createMock(UnitTrackerInterface::class);
        $processTracker->expects($this->once())
            ->method('next')
            ->willReturn($origin);
        $process = new UrlDownloader();
        $res = $process($url, $origin, $processTracker);
        $this->assertEquals($res, $origin);
    }

}

