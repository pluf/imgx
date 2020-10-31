<?php
namespace Pluf\Imgx;

use Intervention\Image\ImageManager;
use Pluf\Scion\UnitTrackerInterface;

/**
 * Downloads the content of the file placed on the given URL into the original file.
 *
 * The original file is used to convert and create new images.
 *
 * @author maso
 * @author hadi
 *        
 */
class UrlDownloader
{

    function __invoke(string $url, string $origin, UnitTrackerInterface $unitTracker)
    {
        if (! is_file($origin)) {
            $manager = new ImageManager(array(
                'driver' => 'imagick'
            ));
            $img = $manager->make($url);
            $img->save($origin);
        }
        return $unitTracker->next();
    }
}

