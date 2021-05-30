<?php
namespace Pluf\Imgx;

use Pluf\Scion\UnitTrackerInterface;
use Psr\Http\Message\ServerRequestInterface;

class UrlFetcher
{

    private string $modulePath;

    private string $extension;

    function __construct(string $modulePath = '/tmp', string $extension = 'jpeg')
    {
        $this->modulePath = $modulePath;
        $this->extension = $extension;
    }

    function __invoke(ServerRequestInterface $request, string $url, UnitTrackerInterface $unitTracker)
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

        $hash = md5($url);
        $target = "$this->modulePath/$hash" . "_w$width-h$height-f$fit-p$position.$this->extension";
        if (is_file($target)) {
            return $target;
        }
        $origin = "$this->modulePath/$hash.$this->extension";
        // check if file exist
        return $unitTracker->next([
            'target' => $target,
            'origin' => $origin,
            'params' => [
                'w' => $width,
                'h' => $height,
                'f' => $fit,
                'p' => $position
            ]
        ]);
    }
}

