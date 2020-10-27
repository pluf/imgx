<?php
namespace Pluf\Tests;

use Pluf\Imgx\Fetcher;
use Pluf\Imgx\OriginMaker;
use Pluf\Imgx\Converter;
use Pluf\Imgx\FileToHttpResponse;

class IntegrationTest
{
    public function mainTest($param) {
        $unit = [
            [
                'condition' => 'regex:#^/imgx#',
                [
                    'condition' => 'regex:#^/api/v2/cms/contents/(?P<name>[^/]+)/content$#',
                    FileToHttpResponse::class,
                    Fetcher::class,
                    OriginMaker::class,
                    Converter::class
                ]
            ],
            [
                'condition' => 'regex:#^http{s}:/#',
                function(){
                    throw new \Exception('Not implemented yet!');
                }
            ]
        ];
    }
}

