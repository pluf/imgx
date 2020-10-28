<?php
namespace Pluf\Imgx;

use Psr\Http\Message\ServerRequestInterface;

class Converter
{
    function __invoke(string $origin, string $target, ServerRequestInterface $request){
        $params = $request->getQueryParams();
        // TODO: create target base on the given parameters
        // TODO: write the target into the given target address (if file does not exsited)
        return $target;
    }
}

