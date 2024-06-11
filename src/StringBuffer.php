<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Interface\BufferInterface;
use Override;

final class StringBuffer implements BufferInterface
{
    public function __construct(
        private string $buffer
    ) {
    }

    #[Override]
    public function append(string $bytes): void
    {
        $this->buffer .= $bytes;
    }

    #[Override]
    public function toString(): string
    {
        return $this->buffer;
    }

    public static function new(string $buffer = ''): self
    {
        return new self($buffer);
    }
}
