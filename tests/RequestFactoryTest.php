<?php
declare(strict_types = 1);
namespace Bnf\Typo3HttpFactory\Tests;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Bnf\Typo3HttpFactory\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function implementsPsr17FactoryInterface()
    {
        $factory = new RequestFactory();
        $this->assertInstanceOf(RequestFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function testRequestHasMethodSet()
    {
        $factory = new RequestFactory();
        $request = $factory->createRequest('POST', '/');
        $this->assertSame('POST', $request->getMethod());
    }

    /**
     * @test
     */
    public function testRequestFactoryHasAWritableEmptyBody()
    {
        $factory = new RequestFactory();
        $request = $factory->createRequest('GET', '/');
        $body = $request->getBody();

        $this->assertInstanceOf(RequestInterface::class, $request);

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
        $factory = new RequestFactory();
        $factory->createRequest('GET', $uri);
    }

    /**
     * @test
     */
    public function raisesExceptionForInvalidMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1436717275);
        $factory = new RequestFactory();
        $factory->createRequest('BOGUS-BODY', '/');
    }
}
