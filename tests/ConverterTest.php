<?php
namespace Pluf\Tests;

use Pluf\Http\Request;
use Pluf\Imgx\Converter;

class ConverterTest extends TestCase
{
    public function resize(){
        $request = new Request();
        $cnv = new Converter();
        $res = $cnv(__DIR__ . '/assets/sample-1.jpeg', '/tmp/imgx-test-sample-1-' . rand() . '.jpeg');
    }
}

