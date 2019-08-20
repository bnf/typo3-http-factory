<?php
declare(strict_types=1);
namespace Bnf\Typo3HttpFactory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\Response;

final class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = new Response('php://temp', $code);
        if ($reasonPhrase !== '') {
            $response = $response->withStatus($code, $reasonPhrase);
        }

        return $response;
    }
}
