<?php
namespace Pluf\Imgx;

class FileToHttpResponse
{

    function __invoke($response, $processTracker)
    {
        $file = $processsTracker->next();
        // TODO: write file to http response
        return $response;
    }
}

