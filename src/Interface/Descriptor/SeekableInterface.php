<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface\Descriptor;

use Ghostwriter\Shell\Interface\RuntimeException;

use const SEEK_SET;

interface SeekableInterface
{
    /**
     * Returns whether the stream is seekable.
     */
    public function isSeekable(): bool;

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception; otherwise, it will perform a seek(0).
     *
     * @throws RuntimeException on failure
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @see seek()
     */
    public function rewind(): void;

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical to the built-in
     *                    PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *                    offset bytes SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset.
     *
     * @throws RuntimeException on failure
     */
    public function seek(int $offset, int $whence = SEEK_SET): void;

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @throws RuntimeException on error
     *
     * @return int Position of the file pointer
     *
     */
    public function tell(): int;
}
