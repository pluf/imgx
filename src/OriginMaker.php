<?php
namespace Pluf\Imgx;

use Intervention\Image\ImageManager;
use Pluf\Scion\UnitTrackerInterface;
use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;

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
    use AssertionTrait;

    function __invoke(EntityManager $entityManager, int $id, string $origin, UnitTrackerInterface $unitTracker)
    {
        // open an image file
        if (! is_file($origin)) {
            // fetch content
            $content = $entityManager->find(Content::class, $id);
            $this->assertTrue($content, 'Content with id {{id} not found', [
                'id' => $id,
            ]);
            
            // copy($content->file_path, $origin);
            $manager = new ImageManager(array(
                'driver' => 'imagick'
            ));
            $img = $manager->make($content->file_path);
            $img->save($origin);
        }
        return $unitTracker->next();
    }
}

