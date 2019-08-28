<?php
declare(strict_types=1);
namespace Bnf\Typo3HttpFactory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Http\Request;
use TYPO3\CMS\Core\Http\Stream;

final class RequestFactory implements RequestFactoryInterface
{
    /**
     * @param string $method
     * @param UriInterface|string $uri
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        $stream = new Stream('php://temp', 'r+');
        return new Request($uri, $method, $stream);
    }
}
