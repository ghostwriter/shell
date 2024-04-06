<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface\Descriptor;

use Ghostwriter\Shell\Interface\RuntimeException;

interface WritableInterface
{
    //    /**
    //     * Returns whether the stream is writable.
    //     */
    //    public function isWritable(): bool;

    /**
     * Write data to the stream.
     *
     * @param string $bytes the string that is to be written
     *
     * @throws RuntimeException on failure
     *
     * @return int returns the number of bytes written to the stream
     *
     */
    public function write(string $bytes): int;
}
