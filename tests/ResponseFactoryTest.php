<?php
declare(strict_types = 1);
namespace Bnf\Typo3HttpFactory\Tests;

use Psr\Http\Message\ResponseFactoryInterface;
use Bnf\Typo3HttpFactory\ResponseFactory;
use PHPUnit\Framework\TestCase;

class ResponseFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function implementsPsr17FactoryInterface()
    {
        $factory = new ResponseFactory();
        $this->assertInstanceOf(ResponseFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function testResponseHasStatusCode200ByDefault()
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function testResponseHasStatusCodeSet()
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse(201);
        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function testResponseHasDefaultReasonPhrase()
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse(301);
        $this->assertSame('Moved Permanently', $response->getReasonPhrase());
    }

    /**
     * @test
     */
    public function testResponseHasCustomReasonPhrase()
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse(201, 'custom message');
        $this->assertSame('custom message', $response->getReasonPhrase());
    }
}
