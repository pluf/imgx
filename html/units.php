<?php
use Pluf\Imgx\Converter;
use Pluf\Imgx\Fetcher;
use Pluf\Imgx\FileToHttpResponse;
use Pluf\Imgx\OriginMaker;
use Pluf\Imgx\UrlDownloader;
use Pluf\Imgx\UrlFetcher;

use Pluf\Core\Process;

return [
    FileToHttpResponse::class,
    [
        new Process\Http\IfPathAndMethodIs('#^/imgx/api/v2/cms/contents/(?P<id>\d+)/content$#', [
            'GET'
        ]),
        Process\Entity\EntityManagerFactory::class,
        Fetcher::class,
        OriginMaker::class,
        Converter::class
    ],
    [
        new Process\Http\IfPathAndMethodIs('#^/imgx/(?P<url>http.+)$#', [
            'GET'
        ]),
        UrlFetcher::class,
        UrlDownloader::class,
        Converter::class
    ],
    function () {
        throw new \Exception('Not implemented yet!');
    }
];