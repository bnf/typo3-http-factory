<?php
declare(strict_types=1);
namespace Bnf\Typo3HttpFactory;

use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;
use TYPO3\CMS\Core\Http\UploadedFile;

final class UploadedFileFactory implements UploadedFileFactoryInterface
{
    public function createUploadedFile(
        StreamInterface $stream,
        int $size = null,
        int $error = \UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ): UploadedFileInterface {
        if ($size === null) {
            $size = $stream->getSize();
            if ($size === null) {
                throw new \InvalidArgumentException('Stream size could not be determined.', 1566823423);
            }
        }

        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
}
