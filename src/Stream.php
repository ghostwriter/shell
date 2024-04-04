<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\FailedToClosePipeException;
use Ghostwriter\Shell\Exception\FailedToReadFromPipeException;
use Ghostwriter\Shell\Exception\FailedToSetStreamBlockingException;
use Ghostwriter\Shell\Exception\FailedToWriteToStdinException;
use Ghostwriter\Shell\Exception\InvalidLengthException;
use Ghostwriter\Shell\Exception\InvalidResourceException;
use Ghostwriter\Shell\Exception\StreamIsNotReadableException;
use Ghostwriter\Shell\Exception\StreamIsNotSeekableException;
use Ghostwriter\Shell\Exception\StreamIsNotWritableException;
use Ghostwriter\Shell\Exception\StreamResourceIsNotAttachedException;
use Ghostwriter\Shell\Exception\UnableToReadFromStreamException;
use Ghostwriter\Shell\Exception\UnableToSeekInStreamException;
use Ghostwriter\Shell\Exception\UnableToTellStreamPositionException;
use Ghostwriter\Shell\Exception\UnableToWriteToStreamException;
use Ghostwriter\Shell\Interface\StreamInterface;
use Throwable;

use const SEEK_CUR;
use const SEEK_END;
use const SEEK_SET;

use function fclose;
use function feof;
use function fread;
use function fseek;
use function fstat;
use function ftell;
use function fwrite;
use function is_resource;
use function str_contains;
use function stream_get_contents;
use function stream_get_meta_data;
use function stream_set_blocking;

final class Stream implements StreamInterface
{
    /**
     * SEEK_CUR - Set position to current location plus offset.
     *
     * @var int
     */
    public const SEEK_CUR = SEEK_CUR;

    /**
     * SEEK_END - Set position to end-of-file plus offset.
     *
     * @var int
     */
    public const SEEK_END = SEEK_END;

    /**
     * SEEK_SET - Set position equal to offset bytes.
     *
     * @var int
     */
    public const SEEK_SET = SEEK_SET;

    /**
     * @param resource $resource
     */
    public function __construct(
        private mixed $resource,
        private readonly bool $readable = false,
        private readonly bool $seekable = false,
        private readonly bool $writable = false,
        private string $buffer = '',
    ) {
        if (! stream_set_blocking($this->resource, false)) {
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
     * @psalm-this-out resource $this->resource
     *
     * @throws InvalidResourceException
     */
    public function assertValidResource(): void
    {
        if (! is_resource($this->resource)) {
            throw new InvalidResourceException();
        }
    }

    public function close(): void
    {
        if ($this->resource === null) {
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
        $resource = $this->resource;

        $this->resource = null;

        return $resource;
    }

    public function endOfFile(): bool
    {
        if (! is_resource($this->resource)) {
            return true;
        }

        return feof($this->resource);
    }

    public function getContents(): string
    {
        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }

        if ($this->readable === false) {
            throw new StreamIsNotReadableException();
        }

        $contents = stream_get_contents($this->resource);
        if ($contents === false) {
            throw new UnableToReadFromStreamException();
        }

        return $this->buffer .= $contents;
    }

    public function getMetadata(?string $key = null): mixed
    {
        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }

        $meta = stream_get_meta_data($this->resource);
        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }

    public function getSize(): ?int
    {
        if ($this->resource === null) {
            return null;
        }

        $stats = fstat($this->resource);
        if ($stats === false) {
            return null;
        }

        return $stats['size'];
    }

    /**
     * Returns true if the stream is readable.
     */
    public function isReadable(): bool
    {
        return $this->resource !== null && $this->readable;
    }

    /**
     * Returns true if the stream is seekable.
     */
    public function isSeekable(): bool
    {
        return $this->resource !== null && $this->seekable;
    }

    /**
     * Returns true if the stream is writable.
     */
    public function isWritable(): bool
    {
        return $this->resource !== null && $this->writable;
    }

    public function read(int $length = 8192): string
    {
        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }

        if ($this->readable === false) {
            throw new StreamIsNotReadableException();
        }

        $bytesRead = fread($this->resource, $length);
        if ($bytesRead === false) {
            throw new UnableToReadFromStreamException();
        }

        $this->buffer.= $bytesRead;

        return $bytesRead;
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $this->assertValidResource();

        if (fseek($this->resource, $offset, $whence) === -1) {
            throw new StreamIsNotSeekableException();
        }

        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }

        if ($this->seekable === false) {
            throw new StreamIsNotSeekableException();
        }

        if (fseek($this->resource, $offset, $whence) === -1) {
            throw new UnableToSeekInStreamException();
        }
    }

    public function tell(): int
    {
        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }

        $offset = ftell($this->resource);
        if ($offset === false) {
            throw new UnableToTellStreamPositionException();
        }

        return $offset;
    }

    public function toString(): string
    {
        if ($this->resource === null || ! $this->readable) {
            return $this->buffer;
        }

        try {
            if ($this->seekable) {
                $this->rewind();
            }

            return $this->getContents();
        } catch (Throwable) {
            return $this->buffer;
        }
    }

    public function write(string $bytes): int
    {
        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }

        if ($this->writable === false) {
            throw new StreamIsNotWritableException();
        }

        $bytesWritten = fwrite($this->resource, $bytes);
        if ($bytesWritten === false) {
            throw new UnableToWriteToStreamException();
        }

        return $bytesWritten;
    }

    /**
     * @psalm-assert resource $this->resource
     *
     * @throws StreamResourceIsNotAttachedException
     */
    private function streamIsUsable(): void
    {
        if ($this->resource === null) {
            throw new StreamResourceIsNotAttachedException();
        }
    }

    //
    //
    //    public function read(int $length = 8192): string
    //    {
    //        if ($length < 1) {
    //            throw new InvalidLengthException();
    //        }
    //
    //        $this->assertValidResource();
    //
    //            $bytesRead = fread($this->resource, $length);
    //
    //            if ($bytesRead === false) {
    //                throw new FailedToReadFromPipeException();
    //            }
    //
    //        return $bytesRead;
    //    }
    //
    //    public function write(string $bytes): void
    //    {
    //        $this->assertValidResource();
    //
    //        $bytesWritten = fwrite($this->resource, $bytes);
    //
    //        if ($bytesWritten === false) {
    //            throw new FailedToWriteToStdinException();
    //        }
    //    }

    /**
     * @param resource|StreamInterface $resourceOrStream
     */
    public static function new(mixed $resourceOrStream): self
    {
        if ($resourceOrStream instanceof StreamInterface) {
            $resourceOrStream = $resourceOrStream->detach();
        }

        if (! is_resource($resourceOrStream)) {
            throw new InvalidResourceException();
        }

        $meta = stream_get_meta_data($resourceOrStream);
        $mode = $meta['mode'] ?? '';
        $read = str_contains($mode, 'r');
        $plus = str_contains($mode, '+');

        return new self(
            $resourceOrStream,
            $read || $plus,
            $meta['seekable'] && fseek($resourceOrStream, 0, self::SEEK_CUR) === 0,
            ! $read || $plus,
        );
    }
}
