<?php
declare(strict_types=1);
namespace Bnf\Typo3HttpFactory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Stream;

final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $stream = new Stream('php://temp', 'r+');
        return new ServerRequest($uri, $method, $stream, [], $serverParams);
    }
}
