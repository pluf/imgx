<?php
namespace Pluf\Imgx;

use Intervention\Image\ImageManager;

use Pluf\Data\Repository\ModelRepository;
use Pluf\Scion\UnitTrackerInterface;

/**
 * Copy the content file into the original file.
 *
 * The original file is used to convert and create new images.
 *
 * @author maso
 * @author hadi
 *        
 */
class OriginMaker
{

    function __invoke(ModelRepository $contentRepository, int $id, string $origin, UnitTrackerInterface $unitTracker)
    {
<<<<<<< HEAD
=======
        // open an image file
        $manager = new ImageManager(array(
            'driver' => 'imagick'
        ));
>>>>>>> branch 'develop' of https://github.com/pluf/imgx.git
        if (! is_file($origin)) {
            $manager = new ImageManager(array(
                'driver' => 'imagick'
            ));
            $content = $contentRepository->getById($id);
            // copy($content->file_path, $origin);
            $img = $manager->make($content->file_path);
            $img->save($origin);
        }
        return $unitTracker->next();
    }
}

