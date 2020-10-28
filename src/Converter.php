<?php
namespace Pluf\Imgx;

use Psr\Http\Message\ServerRequestInterface;

// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

class Converter
{
    function __invoke(string $origin, string $target, array $params){
        // open an image file
        $img = Image::make('public/foo.jpg');
        
        switch ($params['f']){
            case 'cover':
                
                break;
            case 'contain':
                break;
            case 'fill':
            default:
                break;
        }
        // now you are able to resize the instance
        $img->resize(320, 240);
        
        // and insert a watermark for example
        $img->insert('public/watermark.png');
        
        // finally we save the image as a new file
        $img->save('public/bar.jpg');
        // TODO: write the target into the given target address (if file does not exsited)
        return $target;
    }
}

