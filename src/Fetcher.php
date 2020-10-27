<?php
namespace Pluf\Imgx;

class Fetcher
{
    function __invoke($request, ?string $name = null, int $id = 0, $processTracker){
        $file = null;
        // check if file exist
        
        if(!isset($file)){
            $file = $processTracker->next();
        }
        
        
        
        return $file;
    }
}

