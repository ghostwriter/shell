<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface ResultInterface
{
    /** @return list<non-empty-string> */
    public function command(): array;

    public function exitCode(): int;

    public function stderr(): string;

    public function stdout(): string;
}
