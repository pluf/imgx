<?php
namespace Pluf\Imgx;
use Intervention\Image\ImageManager;

class Converter
{

    function __invoke(string $origin, string $target, array $params)
    {
        // open an image file
        $manager = new ImageManager(array('driver' => 'imagick'));
        $img = $manager->make($origin);

        switch ($params['f']) {
            case 'cover':
                $img->fit($params['w'], $params['h'], function ($constraint) {
                    $constraint->upsize();
                }, $params['p']);
                break;
            case 'contain':
                $img->resize($params['w'], $params['h'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                break;
            case 'fill':
            default:
                $img->resize($params['w'], $params['h']);
                break;
        }
        // // Insert a watermark for example
        // $img->insert('public/watermark.png');
        
        // finally we save the image as a new file
        $img->save($target);
        return $target;
    }
}

