<?php
declare(strict_types=1);
namespace Bnf\Typo3HttpFactory;

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use TYPO3\CMS\Core\Http\Stream;

final class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = new Stream('php://temp');
        if ($content !== '') {
            $stream->write($content);
        }
        return $stream;
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $resource = @fopen($filename, $mode);
        if ($resource === false) {
            if ($mode === '' || in_array($mode[0], ['r', 'w', 'a', 'x', 'c'], true) === false) {
                throw new \InvalidArgumentException('The mode ' . $mode . ' is invalid.');
            }

            throw new \RuntimeException('The file ' . $filename . ' cannot be opened.');
        }

        return new Stream($resource);
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}
