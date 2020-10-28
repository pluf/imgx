<?php
namespace Pluf\Imgx;

use Pluf\Data\Repository\ModelRepository;
use Pluf\Scion\ProcessTrackerInterface;
use Intervention\Image\ImageManager;
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

    function __invoke(ModelRepository $contentRepository, int $id, string $origin, ProcessTrackerInterface $processTracker)
    {
        // open an image file
        $manager = new ImageManager(array('driver' => 'imagick'));
        if (! is_file($origin)) {
            $content = $contentRepository->getById($id);
            // copy($content->file_path, $origin);
            $img = $manager->make($content->file_path);
            $img->save($origin);
        }
        return $processTracker->next();
    }
}

