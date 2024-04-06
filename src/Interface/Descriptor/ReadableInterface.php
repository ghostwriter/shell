<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface\Descriptor;

use Ghostwriter\Shell\Interface\DescriptorInterface;
use RuntimeException;

interface ReadableInterface
{
    //    /**
    //     * Returns whether the stream is readable.
    //     */
    //    public function isReadable(): bool;

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return them.
     *                    Fewer than $length bytes may be returned if underlying stream
     *                    call returns fewer bytes.
     *
     * @throws RuntimeException if an error occurs
     *
     * @return string returns the data read from the stream, or an empty string
     *                if no bytes are available
     *
     */
    public function read(int $length = DescriptorInterface::LENGTH): string;

    public function readLine(): string;
}
