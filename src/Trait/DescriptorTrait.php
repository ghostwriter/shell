<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Trait;

use Ghostwriter\Shell\Exception\FailedToClosePipeException;
use Ghostwriter\Shell\Exception\FailedToReadFromStreamException;
use Ghostwriter\Shell\Exception\FailedToSetStreamBlockingException;
use Ghostwriter\Shell\Exception\FailedToWriteToStreamException;
use Ghostwriter\Shell\Exception\InvalidStreamResourceException;
use Ghostwriter\Shell\Exception\StreamIsNotReadableException;
use Ghostwriter\Shell\Exception\StreamIsNotSeekableException;
use Ghostwriter\Shell\Exception\StreamIsNotWritableException;
use Ghostwriter\Shell\Exception\UnableToSeekInStreamException;
use Ghostwriter\Shell\Exception\UnableToTellStreamPositionException;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Throwable;

use function fclose;
use function feof;
use function fgets;
use function fread;
use function fstat;
use function fwrite;
use function is_resource;
use function stream_get_contents;
use function stream_get_meta_data;
use function stream_set_blocking;

trait DescriptorTrait
{
    /**
     * @param null|resource $stream
     *
     * @throws FailedToSetStreamBlockingException
     */
    public function __construct(
        private mixed $stream,
        private string $buffer = '',
    ) {
        $this->assertIsResource();

        if (! stream_set_blocking($this->stream, false)) {
            throw new FailedToSetStreamBlockingException();
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @throws FailedToClosePipeException
     */
    final public function close(): void
    {
        if ($this->stream === null) {
            return;
        }

        $resource = $this->detach();

        if (! is_resource($resource)) {
            return;
        }

        if (! fclose($resource)) {
            throw new FailedToClosePipeException();
        }
    }

    public function detach(): mixed
    {
        $resource = $this->stream;

        $this->stream = null;

        return $resource;
    }

    public function getMetadata(?string $key = null): mixed
    {
        $this->assertIsResource();

        $meta = stream_get_meta_data($this->stream);
        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }

    public function getSize(): ?int
    {
        if (! is_resource($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        if ($stats === false) {
            return null;
        }

        return $stats['size'] ?? null;
    }

    final public function isRunning(): bool
    {
        if (! is_resource($this->stream)) {
            return false;
        }

        return ! feof($this->stream);
    }

    public function read(int $length = DescriptorInterface::LENGTH): string
    {
        $this->assertIsResource();

        //        if ($this->readable === false) {
        //            throw new StreamIsNotReadableException();
        //        }

        $bytesRead = fread($this->stream, $length);
        if ($bytesRead === false) {
            throw new FailedToReadFromStreamException();
        }

        $this->buffer.= $bytesRead;

        return $bytesRead;
    }

    //    /**
    //     * Returns true if the stream is readable.
    //     */
    //    public function isReadable(): bool
    //    {
    //        return $this->readable && is_resource($this->stream);
    //    }
    //
    //    /**
    //     * Returns true if the stream is seekable.
    //     */
    //    public function isSeekable(): bool
    //    {
    //        return $this->seekable && is_resource($this->stream);
    //    }
    //
    //    /**
    //     * Returns true if the stream is writable.
    //     */
    //    public function isWritable(): bool
    //    {
    //        return $this->writable && is_resource($this->stream);
    //    }

    public function readLine(): string
    {
        $this->assertIsResource();

        $bytesRead = fgets($this->stream);
        if ($bytesRead === false) {
            throw new FailedToReadFromStreamException();
        }

        $this->buffer.= $bytesRead;

        return $bytesRead;
    }

    final public function toString(): string
    {
        if ($this->stream === null) {
            return $this->buffer;
        }

        try {
            //            if ($this->seekable) {
            //                $this->rewind();
            //            }

            $this->assertIsResource();

            //            if ($this->readable === false) {
            //                throw new StreamIsNotReadableException();
            //            }

            $contents = stream_get_contents($this->stream);
            if ($contents === false) {
                throw new FailedToReadFromStreamException();
            }

            return $this->buffer .= $contents;
        } catch (Throwable) {
            return $this->buffer;
        }
    }

    //    public function rewind(): void
    //    {
    //        $this->seek(0);
    //    }
    //
    //    public function seek(int $offset, int $whence = SEEK_SET): void
    //    {
    //        $this->assertIsResource();
    //
    //        if (fseek($this->stream, $offset, $whence) === -1) {
    //            throw new StreamIsNotSeekableException();
    //        }
    //
    //        if ($this->stream === null) {
    //            throw new InvalidStreamResourceException();
    //        }
    //
    ////        if ($this->seekable === false) {
    ////            throw new StreamIsNotSeekableException();
    ////        }
    //
    //        if (fseek($this->stream, $offset, $whence) === -1) {
    //            throw new UnableToSeekInStreamException();
    //        }
    //    }

    //    public function tell(): int
    //    {
    //        $this->assertIsResource();
    //
    //        $offset = ftell($this->stream);
    //        if ($offset === false) {
    //            throw new UnableToTellStreamPositionException();
    //        }
    //
    //        return $offset;
    //    }

    //    public function toString(): string
    //    {
    //        if ($this->stream === null) {
    //            return $this->buffer;
    //        }
    //
    //        try {
    //            return $this->asString();
    //        } catch (Throwable) {
    //            return $this->buffer;
    //        }
    //    }

    public function write(string $bytes): int
    {
        $this->assertIsResource();

        //        if ($this->writable === false) {
        //            throw new StreamIsNotWritableException();
        //        }

        $bytesWritten = fwrite($this->stream, $bytes);
        if ($bytesWritten === false) {
            throw new FailedToWriteToStreamException();
        }

        return $bytesWritten;
    }

    /**
     * @psalm-assert resource $this->stream
     *
     * @psalm-this-out resource $this->resource
     *
     * @throws InvalidStreamResourceException
     */
    private function assertIsResource(): void
    {
        if (! is_resource($this->stream)) {
            throw new InvalidStreamResourceException();
        }
    }

    /**
     * @param resource $stream
     *
     * @throws InvalidStreamResourceException
     * @throws FailedToSetStreamBlockingException
     */
    public static function new(mixed $stream): self
    {
        if (! is_resource($stream)) {
            throw new InvalidStreamResourceException();
        }

        //        $meta = stream_get_meta_data($resourceOrStream);
        //        $mode = $meta['mode'] ?? '';
        //        $read = str_contains($mode, 'r');
        //        $plus = str_contains($mode, '+');

        return new self($stream);
    }
}
