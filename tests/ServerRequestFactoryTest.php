<?php
declare(strict_types = 1);
namespace Bnf\Typo3HttpFactory\Tests;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Bnf\Typo3HttpFactory\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

class ServerRequestFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function implementsPsr17FactoryInterface()
    {
        $factory = new ServerRequestFactory();
        $this->assertInstanceOf(ServerRequestFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function testServerRequestHasMethodSet()
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createServerRequest('POST', '/');
        $this->assertSame('POST', $request->getMethod());
    }

    /**
     * @test
     */
    public function testServerRequestFactoryHasAWritableEmptyBody()
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createServerRequest('GET', '/');
        $body = $request->getBody();

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertSame('', $body->__toString());
        $this->assertSame(0, $body->getSize());
        $this->assertTrue($body->isSeekable());

        $body->write('Foo');
        $this->assertSame(3, $body->getSize());
        $this->assertSame('Foo', $body->__toString());
    }

    /**
     * @return array
     */
    public function invalidRequestUriDataProvider()
    {
        return [
            'true'     => [true],
            'false'    => [false],
            'int'      => [1],
            'float'    => [1.1],
            'array'    => [['http://example.com']],
            'stdClass' => [(object)['href' => 'http://example.com']],
        ];
    }

    /**
     * @dataProvider invalidRequestUriDataProvider
     * @test
     */
    public function constructorRaisesExceptionForInvalidUri($uri)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1436717272);
        $factory = new ServerRequestFactory();
        $factory->createServerRequest('GET', $uri);
    }

    /**
     * @test
     */
    public function raisesExceptionForInvalidMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1436717275);
        $factory = new ServerRequestFactory();
        $factory->createServerRequest('BOGUS-BODY', '/');
    }
}
