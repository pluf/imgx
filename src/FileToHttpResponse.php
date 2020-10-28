<?php
namespace Pluf\Imgx;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Exception;
use Pluf\Scion\ProcessTrackerInterface;

/**
 * Converts response file to a http response
 *
 * @author maso
 * @author hadi
 */
class FileToHttpResponse
{

    /**
     * Defines response cache police
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
     * @var string
     */
    private string $cacheControl;

    private string $mimeTypes;

    /**
     * Creates new instance of the process
     *
     * It is possible to change process options. All options passed as a parameter.
     *
     * @param string $cacheControl
     *            sets the cache policy
     */
    public function __construct(string $cacheControl = 'public,immutable', string $mimeTypes = '/etc/mime.types')
    {
        $this->cacheControl = $cacheControl;
        $this->mimeTypes = $mimeTypes;
    }

    /**
     * Convert file to a reponse
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param ProcessTrackerInterface $processTracker
     * @throws Exception
     * @return \Psr\Http\Message\ResponseInterface
     */
    function __invoke(ServerRequestInterface $request, ResponseInterface $response, ProcessTrackerInterface $processTracker)
    {
        $filePath = $processTracker->next();
        $fileName = basename($filePath);

        if (! file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }

        // default action is to send the entire file
        $byteOffset = 0;
        $byteLength = $fileSize = filesize($filePath);
        $fileName = self::cleanFileName($fileName);

        // remove headers hat might unnecessarily clutter up the output
        $server = $request->getServerParams();

        $match = [];
        if (isset($server['HTTP_RANGE']) && preg_match('%bytes=(\d+)-(\d+)?%i', $server['HTTP_RANGE'], $match)) {
            $byteOffset = (int) $match[1];

            if (isset($match[2])) {
                $finishBytes = (int) $match[2];
                $byteLength = $finishBytes + 1;
            } else {
                $finishBytes = $fileSize - 1;
            }
            $response = $response->withStatus(206, 'Partial Content')->withHeader('Content-Range', "bytes {$byteOffset}-{$finishBytes}/{$fileSize}");
        }

        $byteRange = $byteLength - $byteOffset;

        $bufferSize = 512 * 16;
        $bytePool = $byteRange;

        if (! $fh = fopen($filePath, 'r')) {
            throw new Exception("Could not get filehandler for reading: $filePath");
        }

        if (fseek($fh, $byteOffset, SEEK_SET) == - 1) {
            throw new Exception("Could not seek to offset $byteOffset in file: $filePath");
        }

        while ($bytePool > 0) {
            $chunkSizeRequested = min($bufferSize, $bytePool);
            $buffer = fread($fh, $chunkSizeRequested);
            $chunkSizeActual = strlen($buffer);
            if ($chunkSizeActual == 0) {
                throw new \Exception("Chunksize became 0");
            }
            $bytePool -= $chunkSizeActual;
            $response->getBody()->write($buffer);
        }

        return $response->withHeader('Cache-Control', $this->cacheControl)
            ->withoutHeader('Pragma')
            ->withHeader('Content-Type', $this->getMimeType($fileName))
            ->withHeader('Accept-Ranges', 'bytes')
            ->withHeader('Content-Disposition', "attachment; filename=\"{$fileName}\"")
            ->withHeader('Content-Length', $byteRange);
    }

    private static function cleanFileName($fileName)
    {
        // clean up filename
        $invalidChars = array(
            '<',
            '>',
            '?',
            '"',
            ':',
            '|',
            '\\',
            '/',
            '*',
            '&'
        );
        $fileName = str_replace($invalidChars, '', $fileName);
        // normalize to prevent utf8 problems
        // $fileName = preg_replace('/\p{Mn}/u', '', Normalizer::normalize($fileName, Normalizer::FORM_KD));
        return $fileName;
    }

    /**
     * Find the mime type of a file.
     *
     * Use /etc/mime.types to find the type.
     *
     * @param
     *            string Filename/Filepath
     * @param
     *            array Mime type found or 'application/octet-stream', basename,
     *            extension
     */
    public function getMimeType($file)
    {
        static $mimes = null;
        $info = pathinfo($file);
        if (isset($info['extension'])) {
            // load mimes
            if ($mimes == null) {
                $mimes = array();
                $filecontent = @file_get_contents($this->mimeTypes);
                if ($filecontent !== false) {
                    $mimes = preg_split("/\015\012|\015|\012/", $filecontent);
                }
            }
            foreach ($mimes as $mime) {
                if ('#' != substr($mime, 0, 1)) {
                    $elts = preg_split('/ |\t/', $mime, - 1, PREG_SPLIT_NO_EMPTY);
                    if (in_array($info['extension'], $elts)) {
                        return $elts[0];
                    }
                }
            }
        }
        return 'application/octet-stream';
    }
}

