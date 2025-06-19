<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Trait;

use Ghostwriter\Shell\Exception\EmptyStringException;
use Ghostwriter\Shell\Exception\FailedToClosePipeException;
use Ghostwriter\Shell\Exception\FailedToReadFromStreamException;
use Ghostwriter\Shell\Exception\FailedToSetStreamBlockingException;
use Ghostwriter\Shell\Exception\FailedToWriteToStreamException;
use Ghostwriter\Shell\Exception\MissingStreamMetadataException;
use Ghostwriter\Shell\Exception\StreamIsNotReadableException;
use Ghostwriter\Shell\Exception\StreamIsNotResourceException;
use Ghostwriter\Shell\Exception\StreamIsNotWritableException;
use Ghostwriter\Shell\Interface\BufferInterface;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Ghostwriter\Shell\StringBuffer;
use Override;

use function array_key_exists;
use function fclose;
use function feof;
use function fgets;
use function fread;
use function fstat;
use function fwrite;
use function is_resource;
use function is_writable;
use function mb_trim;
use function str_contains;
use function stream_get_contents;
use function stream_get_meta_data;
use function stream_set_blocking;

trait DescriptorTrait
{
    /**
     * @param null|resource $stream
     *
     * @throws FailedToSetStreamBlockingException
     * @throws StreamIsNotResourceException
     */
    public function __construct(
        private mixed $stream,
        private readonly BufferInterface $buffer,
    ) {
        $this->assertNonBlockingResource();
    }

    /**
     * @param resource $stream
     *
     * @throws StreamIsNotResourceException
     * @throws FailedToSetStreamBlockingException
     */
    public static function new(mixed $stream): self
    {
        if (! is_resource($stream)) {
            throw new StreamIsNotResourceException();
        }

        //        $meta = stream_get_meta_data($resourceOrStream);
        //        $mode = $meta['mode'] ?? '';
        //        $read = str_contains($mode, 'r');
        //        $plus = str_contains($mode, '+');

        return new self($stream, StringBuffer::new());
    }

    /**
     * @throws FailedToClosePipeException
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @throws FailedToClosePipeException
     */
    #[Override]
    final public function close(): void
    {
        if (null === $this->stream) {
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

    /**
     * @return null|resource
     */
    #[Override]
    final public function detach(): mixed
    {
        $resource = $this->stream;

        $this->stream = null;

        return $resource;
    }

    /**
     *
     * @throws StreamIsNotResourceException
     * @throws MissingStreamMetadataException
     *
     * @return array{
     *      timed_out: bool,
     *      blocked: bool,
     *      eof: bool,
     *      unread_bytes: int,
     *      stream_type: string,
     *      wrapper_type: string,
     *      wrapper_data: mixed,
     *      mode: string,
     *      seekable: bool,
     *      uri: string,
     *      crypto: array,
     *      mediatype: string
     *   }|bool|mixed|null
     *
     */
    final public function getMetadata(?string $key = null): mixed
    {
        $this->assertIsResource();

        $meta = stream_get_meta_data($this->stream);

        if (null === $key) {
            return $meta;
        }

        if (! array_key_exists($key, $meta)) {
            throw new MissingStreamMetadataException();
        }

        return $meta[$key];
    }

    final public function getSize(): ?int
    {
        if (! is_resource($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        if (false === $stats) {
            return null;
        }

        return $stats['size'] ?? null;
    }

    #[Override]
    final public function isRunning(): bool
    {
        if (! is_resource($this->stream)) {
            return false;
        }

        return ! feof($this->stream);
    }

    /**
     * @throws FailedToReadFromStreamException
     * @throws StreamIsNotResourceException
     * @throws StreamIsNotReadableException
     */
    #[Override]
    final public function read(int $length = DescriptorInterface::LENGTH): string
    {
        $this->assertIsReadable();

        $bytesRead = fread($this->stream, $length);
        if (false === $bytesRead) {
            throw new FailedToReadFromStreamException();
        }

        $this->buffer->append($bytesRead);

        return $bytesRead;
    }

    /**
     * @throws FailedToReadFromStreamException
     * @throws StreamIsNotResourceException
     */
    #[Override]
    final public function readLine(): string
    {
        $this->assertIsResource();

        $bytesRead = fgets($this->stream);
        if (false === $bytesRead) {
            throw new FailedToReadFromStreamException();
        }

        $this->buffer->append($bytesRead);

        return $bytesRead;
    }

    /**
     * @throws FailedToReadFromStreamException
     * @throws StreamIsNotResourceException
     * @throws StreamIsNotReadableException
     */
    #[Override]
    final public function toString(): string
    {
        if (null === $this->stream) {
            return $this->buffer->toString();
        }

        $this->assertIsReadable();

        $contents = stream_get_contents($this->stream);
        if (false === $contents) {
            throw new FailedToReadFromStreamException();
        }

        return $this->buffer->toString() . $contents;
    }

    /**
     * @throws EmptyStringException
     * @throws FailedToWriteToStreamException
     * @throws StreamIsNotResourceException
     * @throws StreamIsNotWritableException
     *
     * @return positive-int
     */
    #[Override]
    final public function write(string $bytes): int
    {
        $this->assertNotEmpty($bytes);

        $this->assertIsWritable();

        $bytesWritten = fwrite($this->stream, $bytes);
        if (false === $bytesWritten) {
            throw new FailedToWriteToStreamException();
        }

        return $bytesWritten;
    }

    /**
     * @psalm-assert resource $this->stream
     *
     * @throws StreamIsNotReadableException
     * @throws StreamIsNotResourceException
     */
    private function assertIsReadable(): void
    {
        $this->assertIsResource();

        $metaData = stream_get_meta_data($this->stream);

        $mode = $metaData['mode'] ?? '';

        if (! str_contains($mode, 'r')) {
            return;
        }

        if (! str_contains($mode, '+')) {
            return;
        }

        throw new StreamIsNotReadableException();
    }

    /**
     * @psalm-assert resource $this->stream
     *
     * @psalm-this-out resource $this->resource
     *
     * @throws StreamIsNotResourceException
     */
    private function assertIsResource(): void
    {
        if (! is_resource($this->stream)) {
            throw new StreamIsNotResourceException();
        }
    }

    /**
     * @psalm-assert resource $this->stream
     *
     * @psalm-this-out resource $this->resource
     *
     * @throws StreamIsNotResourceException
     * @throws StreamIsNotWritableException
     */
    private function assertIsWritable(): void
    {
        $this->assertIsResource();

        if (! is_writable($this->stream)) {
            throw new StreamIsNotWritableException();
        }
    }

    /**
     * @psalm-assert resource $this->stream
     *
     * @psalm-this-out resource $this->resource
     *
     * @throws FailedToSetStreamBlockingException
     * @throws StreamIsNotResourceException
     */
    private function assertNonBlockingResource(): void
    {
        $this->assertIsResource();

        if (! stream_set_blocking($this->stream, false)) {
            throw new FailedToSetStreamBlockingException();
        }
    }

    /**
     * @psalm-assert-if-true non-empty-string $bytes
     *
     * @throws EmptyStringException
     */
    private function assertNotEmpty(string $bytes): void
    {
        if (mb_trim($bytes) === '') {
            throw new EmptyStringException();
        }
    }
}
