<?php
namespace Pluf\Imgx;

use Psr\Http\Message\ServerRequestInterface;
use Pluf\Scion\ProcessTracker;

class Fetcher
{

    function __invoke(ServerRequestInterface $request, int $id, ProcessTracker $processTracker)
    {
        // Parameters:
        // width,
        // height,
        // fit [fill, cover, contain],
        // position [top, bottom, left, right, top-right, top-left, bottom-right, bottom-left, center],
        // compress
        $params = $request->getQueryParams();
        $width = array_key_exists('w', $params) ? $params['w'] : 'auto';
        $height = array_key_exists('h', $params) ? $params['h'] : 'auto';
        $fit = array_key_exists('f', $params) ? $params['f'] : 'fill';
        $position = array_key_exists('p', $params) ? $params['p'] : 'center';

        // FIXME: fetch storage path from pluf settings
        $modulePath = '/tmp/storage/imgx';
        $target = "$modulePath/$id\_w$width-h$height-f$fit-p$position.jpeg";
        if (is_file($target)) {
            return $target;
        }
        $origin = "$modulePath/$id.jpeg";
        // check if file exist
       return $processTracker->next([
            'target' => $target,
            'oregin' => $origin,
            'params' => [
                'w' => $width,
                'h' => $height,
                'f' => $fit,
                'p' => $position
            ]
        ]);
    }
}

