<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface ResultInterface
{
    public function exitCode(): int;

    public function stderr(): string;

    public function stdout(): string;
}
