<?php
use Pluf\Imgx\Converter;
use Pluf\Imgx\Fetcher;
use Pluf\Imgx\FileToHttpResponse;
use Pluf\Imgx\OriginMaker;
use Pluf\Scion\Process\HttpProcess;

return [
    [
        new HttpProcess('#^/imgx#', [
            'GET'
        ]),
        [
            new HttpProcess('#^/api/v2/cms/contents/(?P<id>\d+)/content$#'),
            FileToHttpResponse::class,
            new Fetcher(__DIR__ . '/../tests/assets'),
            OriginMaker::class,
            Converter::class
        ]
    ],
    function () {
        throw new \Exception('Not implemented yet!');
    }
];