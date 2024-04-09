<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Interface\ResultInterface;

final readonly class Result implements ResultInterface
{
    public function __construct(
        private int $exitCode,
        private string $stdout,
        private string $stderr,
    ) {}

    public function exitCode(): int
    {
        return $this->exitCode;
    }

    public function stderr(): string
    {
        return $this->stderr;
    }

    public function stdout(): string
    {
        return $this->stdout;
    }

    public static function new(int $exitCode, string $stdout, string $stderr): self
    {
        return new self($exitCode, $stdout, $stderr);
    }
}
