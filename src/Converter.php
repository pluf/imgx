<?php
namespace Pluf\Imgx;

class Converter
{
    function __invoke(string $origin, string $target, $request){
        $params = $request->params();
        // TODO: create target base on the given parameters
        // TODO: write the target into the given target address (if file does not exsited)
        return $target;
    }
}

