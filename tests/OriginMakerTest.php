<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Imgx\Content;
use Pluf\Imgx\OriginMaker;
use Pluf\Orm\EntityManager;
use Pluf\Scion\UnitTrackerInterface;

class OriginMakerTest extends TestCase
{

    public static $repository;

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

        // maso, 2021: repository mock
        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->never())
            ->method('find')
            ->willReturn(null);

        $process = new OriginMaker();
        $res = $process($entityManagerMock, 1, $origin, $processTracker);
        $this->assertEquals($res, $origin);
    }

    /**
     *
     * @test
     */
    public function nonexistedOriginTest()
    {
        $origin = '/tmp/test-assets-sample-' . rand();
        $content = new Content();
        $content->id = 1;
        $content->file_path = __DIR__ . '/assets/sample-1.jpeg';
        $content->file_size = filesize($content->file_path);

        // process tracker mock
        $processTracker = $this->createMock(UnitTrackerInterface::class);
        $processTracker->expects($this->once())
            ->method('next')
            ->willReturn($origin);

        // maso, 2021: repository mock
        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo(Content::class), $this->equalTo($content->id))
            ->willReturn($content);

        $process = new OriginMaker();
        $res = $process($entityManagerMock, $content->id, $origin, $processTracker);
        $this->assertEquals($res, $origin);
    }
}

