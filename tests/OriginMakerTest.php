<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Repository;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Data\Repository\ModelRepository;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf\Db\Connection;
use Pluf\Imgx\Content;
use Pluf\Imgx\OriginMaker;
use Pluf\Scion\UnitTrackerInterface;

class OriginMakerTest extends TestCase
{

    public static $repository;

    /**
     *
     * @beforClass
     */
    public static function setUpBeforeClass(): void
    {
        $connection = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        switch ($GLOBALS['DB_SCHEMA']) {
            case 'sqlite':
                $schema = new SQLiteSchema([]);
                break;
            case 'mysql':
                $schema = new SQLiteSchema([]);
                break;
        }
        $mdr = new ModelDescriptionRepository([
            new MapModelDescriptionLoader([
                Content::class => require __DIR__ . '/../md/ContentMd.php'
            ])
        ]);

        $schema->createTables(
            // DB connection
            $connection, 
            // Model description
            $mdr->getModelDescription(Content::class));

        self::$repository = Repository::getInstance([
            'connection' => $connection, // Connection
            'schema' => $schema, // Schema builder (optionall)
            'mdr' => $mdr, // storage of model descriptions (optionall)
            'model' => Content::class
        ]);
    }

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

        // TODO: maso, 2020: repository mock
        $repoMock = $this->createMock(ModelRepository::class);
        $repoMock->expects($this->never())
            ->method('getById')
            ->willReturn(null);

        $process = new OriginMaker();
        $res = $process($repoMock, 1, $origin, $processTracker);
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

        // TODO: maso, 2020: repository mock
        $repoMock = $this->createMock(ModelRepository::class);
        $repoMock->expects($this->once())
            ->method('getById')
            ->with($this->equalTo($content->id))
            ->willReturn($content);

        $process = new OriginMaker();
        $res = $process($repoMock, $content->id, $origin, $processTracker);
        $this->assertEquals($res, $origin);
    }

    /**
     *
     * @test
     */
    public function nonexistedOriginIntegerationTest()
    {
        $origin = '/tmp/test-assets-sample-' . rand();
        $content = new Content();
        $content->file_path = __DIR__ . '/assets/sample-1.jpeg';
        $content->file_size = filesize($content->file_path);
        self::$repository->create($content);

        // process tracker mock
        $processTracker = $this->createMock(UnitTrackerInterface::class);
        $processTracker->expects($this->once())
            ->method('next')
            ->willReturn($origin);

        $process = new OriginMaker();
        $res = $process(self::$repository, $content->id, $origin, $processTracker);
        $this->assertEquals($res, $origin);
    }
}

