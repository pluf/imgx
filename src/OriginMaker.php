<?php
namespace Pluf\Imgx;

class OriginMaker
{

    function __invoke($processTracker, ?string $name = null, int $id = 0)
    {
        $origin = null;
        // TODO: provide address of the origin file
        return $processTracker->next([
            'origin' => $origin
        ]);
    }
}

