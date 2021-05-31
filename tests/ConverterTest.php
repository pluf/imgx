<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Imgx\Converter;

class ConverterTest extends TestCase
{

    /**
     *
     * @test
     */
    public function resizeFillCenter()
    {
        $origin = __DIR__ . '/assets/sample-1.jpeg';
        $target = '/tmp/imgx-test-sample-1-' . rand() . '.jpeg';
        $params = [
            'w' => 100,
            'h' => 100,
            'f' => 'fill',
            'p' => 'center'
        ];
        $cnv = new Converter();
        $res = $cnv($origin, $target, $params);
        $this->assertTrue(is_file($res));
    }
    
    /**
     *
     * @test
     */
    public function resizeCoverTop()
    {
        $origin = __DIR__ . '/assets/sample-1.jpeg';
        $target = '/tmp/imgx-test-sample-1-' . rand() . '.jpeg';
        $params = [
            'w' => 100,
            'h' => 100,
            'f' => 'cover',
            'p' => 'top'
        ];
        $cnv = new Converter();
        $res = $cnv($origin, $target, $params);
        $this->assertTrue(is_file($res));
    }
    
    /**
     *
     * @test
     */
    public function resizeContainTopRight()
    {
        $origin = __DIR__ . '/assets/sample-1.jpeg';
        $target = '/tmp/imgx-test-sample-1-' . rand() . '.jpeg';
        $params = [
            'w' => 500,
            'h' => 300,
            'f' => 'contain',
            'p' => 'top-right'
        ];
        $cnv = new Converter();
        $res = $cnv($origin, $target, $params);
        $this->assertTrue(is_file($res));
    }
}

