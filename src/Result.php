<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Interface\ResultInterface;
use Override;

final readonly class Result implements ResultInterface
{
    /** @param list<non-empty-string> $command */
    public function __construct(
        public array $command,
        private int $exitCode,
        private string $stdout,
        private string $stderr,
    ) {}

    /** @param list<non-empty-string> $command */
    public static function new(array $command, int $exitCode, string $stdout, string $stderr): self
    {
        return new self($command, $exitCode, $stdout, $stderr);
    }

    /** @return list<non-empty-string> */
    #[Override]
    public function command(): array
    {
        return $this->command;
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
