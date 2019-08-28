<?php
declare(strict_types = 1);
namespace Bnf\Typo3HttpFactory\Tests;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Bnf\Typo3HttpFactory\UriFactory;
use PHPUnit\Framework\TestCase;

class UriFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function implementsPsr17FactoryInterface()
    {
        $factory = new UriFactory();
        $this->assertInstanceOf(UriFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function testUriIsCreated()
    {
        $factory = new UriFactory();
        $uri = $factory->createUri('https://user:pass@domain.localhost:3000/path?query');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame('user:pass', $uri->getUserInfo());
        $this->assertSame('domain.localhost', $uri->getHost());
        $this->assertSame(3000, $uri->getPort());
        $this->assertSame('/path', $uri->getPath());
        $this->assertSame('query', $uri->getQuery());
    }
}
