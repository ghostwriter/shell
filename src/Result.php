<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Interface\ResultInterface;
use Override;

final readonly class Result implements ResultInterface
{
    public function __construct(
        private int $exitCode,
        private string $stdout,
        private string $stderr,
    ) {}

    public static function new(int $exitCode, string $stdout, string $stderr): self
    {
        return new self($exitCode, $stdout, $stderr);
    }

    #[Override]
    public function exitCode(): int
    {
        return $this->exitCode;
    }

    #[Override]
    public function stderr(): string
    {
        return $this->stderr;
    }

    #[Override]
    public function stdout(): string
    {
        return $this->stdout;
    }
}
