<?php
use Pluf\Imgx\Converter;
use Pluf\Imgx\Fetcher;
use Pluf\Imgx\FileToHttpResponse;
use Pluf\Imgx\OriginMaker;
use Pluf\Scion\Process\HttpProcess;
use Pluf\Imgx\UrlDownloader;
use Pluf\Imgx\UrlFetcher;

return [
    [
        new HttpProcess('#^/imgx#', [
            'GET'
        ]),
        FileToHttpResponse::class,
        [
            new HttpProcess('#^/api/v2/cms/contents/(?P<id>\d+)/content$#'),
            new Fetcher(__DIR__ . '/../tests/assets'),
            OriginMaker::class,
            Converter::class
        ],
        [
            new HttpProcess('#^/(?P<url>http.+)#'),
            new UrlFetcher(__DIR__ . '/../tests/assets'),
            UrlDownloader::class,
            Converter::class
        ]
    ],
    function () {
        throw new \Exception('Not implemented yet!');
    }
];