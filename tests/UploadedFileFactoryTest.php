<?php
declare(strict_types = 1);
namespace Bnf\Typo3HttpFactory\Tests;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Bnf\Typo3HttpFactory\UploadedFileFactory;
use PHPUnit\Framework\TestCase;

class UploadedFileFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function implementsPsr17FactoryInterface()
    {
        $factory = new UploadedFileFactory();
        $this->assertInstanceOf(UploadedFileFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function testCreateUploadedFile()
    {
        $streamProphecy = $this->prophesize(StreamInterface::class);
        $factory = new UploadedFileFactory();
        $uploadedFile = $factory->createUploadedFile($streamProphecy->reveal(), 0);

        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);
        $this->assertSame(UPLOAD_ERR_OK, $uploadedFile->getError());
        $this->assertNull($uploadedFile->getClientFileName());
        $this->assertNull($uploadedFile->getClientMediaType());
    }

    /**
     * @test
     */
    public function testCreateUploadedFileWithParams()
    {
        $streamProphecy = $this->prophesize(StreamInterface::class);
        $factory = new UploadedFileFactory();
        $uploadedFile = $factory->createUploadedFile($streamProphecy->reveal(), 0, UPLOAD_ERR_NO_FILE, 'filename.html', 'text/html');

        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);
        $this->assertSame(UPLOAD_ERR_NO_FILE, $uploadedFile->getError());
        $this->assertSame('filename.html', $uploadedFile->getClientFileName());
        $this->assertSame('text/html', $uploadedFile->getClientMediaType());
    }

    /**
     * @test
     */
    public function testCreateUploadedFileCreateSizeFromStreamSize()
    {
        $streamProphecy = $this->prophesize(StreamInterface::class);
        $streamProphecy->getSize()->willReturn(5);

        $factory = new UploadedFileFactory();
        $uploadedFile = $factory->createUploadedFile($streamProphecy->reveal());

        $this->assertSame(5, $uploadedFile->getSize());
    }

    /**
     * @test
     */
    public function testCreateUploadedFileThrowsExceptionWhenStreamSizeCanNotBeDetermined()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1566823423);

        $streamProphecy = $this->prophesize(StreamInterface::class);
        $streamProphecy->getSize()->willReturn(null);

        $factory = new UploadedFileFactory();
        $uploadedFile = $factory->createUploadedFile($streamProphecy->reveal());

        $this->assertSame(3, $uploadedFile->getSize());
    }
}
