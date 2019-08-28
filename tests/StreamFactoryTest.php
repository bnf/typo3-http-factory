<?php
declare(strict_types = 1);
namespace Bnf\Typo3HttpFactory\Tests;

use Psr\Http\Message\StreamFactoryInterface;
use Bnf\Typo3HttpFactory\StreamFactory;
use PHPUnit\Framework\TestCase;

class StreamFactoryTest extends TestCase
{
    const TEMP_FILE = 'temp.file';

    public function setUp(): void {
        register_shutdown_function(function() {
            if(file_exists(self::TEMP_FILE)) {
                unlink(self::TEMP_FILE);
            }
        });
    }
    /**
     * @test
     */
    public function implementsPsr17FactoryInterface()
    {
        $factory = new StreamFactory();
        $this->assertInstanceOf(StreamFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function testCreateStreamReturnsEmptyStreamByDefault()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream();
        $this->assertSame('', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamFromEmptyString()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream('');
        $this->assertSame('', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamFromNonEmptyString()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream('Foo');
        $this->assertSame('Foo', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamReturnsWritableStream()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream();
        $stream->write('Foo');
        $this->assertSame('Foo', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamReturnsAppendableStream()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream('Foo');
        $stream->write('Bar');
        $this->assertSame('FooBar', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamFromFile()
    {
        $fileName = self::TEMP_FILE;
        file_put_contents($fileName, 'Foo');

        $factory = new StreamFactory();
        $stream = $factory->createStreamFromFile($fileName);
        $this->assertSame('Foo', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamFromFileWithMode()
    {
        $fileName = self::TEMP_FILE;

        $factory = new StreamFactory();
        $stream = $factory->createStreamFromFile($fileName, 'w');
        $stream->write('Foo');

        $contents = file_get_contents($fileName);
        $this->assertSame('Foo', $contents);
    }

    /**
     * @test
     */
    public function testCreateStreamFromFileWithInvalidMode()
    {
        $fileName = self::TEMP_FILE;
        touch($fileName);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1566823434);
        $factory = new StreamFactory();
        $factory->createStreamFromFile($fileName, 'z');
    }

    /**
     * @test
     */
    public function testCreateStreamFromFileWithMissingFile()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(1566823435);
        $factory = new StreamFactory();
        $factory->createStreamFromFile('unavailable_file.txt', 'r');
    }

    /**
     * @test
     */
    public function testCreateStreamFromResource()
    {
        $fileName = self::TEMP_FILE;
        touch($fileName);
        file_put_contents($fileName, 'Foo');

        $resource = fopen($fileName, 'r');

        $factory = new StreamFactory();
        $stream = $factory->createStreamFromResource($resource);
        $this->assertSame('Foo', $stream->__toString());
    }

    /**
     * @test
     */
    public function testCreateStreamResourceFromInvalidResource()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1566853697);
        $resource = xml_parser_create();

        $factory = new StreamFactory();
        $factory->createStreamFromResource($resource);
    }
}
