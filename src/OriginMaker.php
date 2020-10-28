<?php
namespace Pluf\Imgx;

use Pluf\Data\Repository\ModelRepository;
use Pluf\Scion\ProcessTrackerInterface;
use Intervention\Image\ImageManagerStatic as Image;
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
        if (! is_file($origin)) {
            $content = $contentRepository->getById($id);
            // copy($content->file_path, $origin);
            $img = Image::make($content->file_path);
            $img->save($origin);
        }
        return $processTracker->next();
    }
}

