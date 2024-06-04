<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface BufferInterface
{
    public function append(string $bytes): void;

    public function toString(): string;
}
