<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

use Ghostwriter\Shell\Exception\FailedToClosePipeException;
use Ghostwriter\Shell\Interface\Descriptor\ReadableInterface;
use Ghostwriter\Shell\Interface\Descriptor\WritableInterface;
use RuntimeException;

interface DescriptorInterface extends ReadableInterface, WritableInterface
{
    /**
     * @var int
     */
    public const int LENGTH = 4096;

    /**
     * Closes the stream and any underlying resources.
     *
     * @throws FailedToClosePipeException
     */
    public function close(): void;

    /**
     * Separates any underlying stream resource from the Descriptor.
     *
     * After the stream has been detached, the descriptor is in an unusable state.
     *
     * @return null|resource Underlying PHP stream, if any
     */
    public function detach(): mixed;

    /**
     * Returns whether the stream is open.
     */
    public function isRunning(): bool;

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * @throws RuntimeException if unable to read or an error occurs while reading
     */
    public function toString(): string;
}
