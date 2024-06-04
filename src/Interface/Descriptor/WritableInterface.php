<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface\Descriptor;

use Throwable;

interface WritableInterface
{
    /**
     * Write data to the stream.
     *
     * @param string $bytes the string that is to be written
     *
     * @throws Throwable on failure
     *
     * @return int returns the number of bytes written to the stream
     *
     */
    public function write(string $bytes): int;
}
