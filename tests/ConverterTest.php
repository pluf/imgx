<?php
namespace Pluf\Tests;

use PHPUnit\Framework\TestCase;
use Pluf\Http\RequestFactory;
use Pluf\Imgx\Converter;

class ConverterTest extends TestCase
{

    /**
     *
     * @test
     */
    public function resize()
    {
        $requestFactory = new RequestFactory();
        $request = $requestFactory
            ->createRequest('GET', '/imgx/api/v2/cms/contents/1/contetn')
            ->withQueryParams([
                'w' => 100,
                'h' => 100
            ]);

        $target = '/tmp/imgx-test-sample-1-' . rand() . '.jpeg';

        $cnv = new Converter();
        $res = $cnv(__DIR__ . '/assets/sample-1.jpeg', $target, $request);

        $this->assertTrue(is_file($res));
    }
}

