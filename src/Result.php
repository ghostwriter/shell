<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Interface\ProcessInterface;
use Ghostwriter\Shell\Interface\ResultInterface;
use Throwable;

final readonly class Result implements ResultInterface
{
    public function __construct(
        private int $exitCode,
        private string $stdout,
        private string $stderr,
    ) {
    }

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

    /**
     * @throws Throwable
     */
    public static function new(ProcessInterface $process): ResultInterface
    {
        return new self($process->close(), $process->stdout()->toString(), $process->stderr()->toString());
    }
}
