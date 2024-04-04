<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\FailedToExecuteCommandException;
use Ghostwriter\Shell\Exception\FailedToOpenProcessException;
use Ghostwriter\Shell\Exception\FailedToTerminateProcessException;
use Ghostwriter\Shell\Exception\ProcessIsNotRunningException;
use Ghostwriter\Shell\Interface\CommandInterface;
use Ghostwriter\Shell\Interface\EnvironmentVariablesInterface;
use Ghostwriter\Shell\Interface\ProcessInterface;
use Ghostwriter\Shell\Interface\StatusInterface;
use Ghostwriter\Shell\Interface\StreamInterface;
use Ghostwriter\Shell\Interface\WorkingDirectoryInterface;

use function hrtime;
use function is_resource;
use function proc_close;
use function proc_open;
use function proc_terminate;
use function restore_error_handler;
use function set_error_handler;

final readonly class Process implements ProcessInterface
{
    /**
     * @var array{0:array{0:string,1:string},1:array{0:string,1:string},2:array{0:string,1:string}}
     */
    public const DESCRIPTORS = [
        0 => ['pipe', 'rb'], // stdin
        1 => ['pipe', 'wb'], // stdout
        2 => ['pipe', 'wb'], // stderr
    ];

    /**
     * @param resource $resource
     */
    public function __construct(
        private CommandInterface $command,
        private mixed $resource,
        private StreamInterface $stdin,
        private StreamInterface $stdout,
        private StreamInterface $stderr,
    ) {
    }

    public function close(): int
    {
        return proc_close($this->resource);
    }

    public function command(): CommandInterface
    {
        return $this->command;
    }

    public function isRunning(): bool
    {
        return is_resource($this->resource);
    }

    /**
     * Returns true if the process is passed the maximum execution time.
     */
    public function isTimedOut(): bool
    {
        $startTime = hrtime(true);
        $maximumExecutionTime = 60; // 60 seconds
        return ($startTime + $maximumExecutionTime) < hrtime(true);
    }

    public function pid(): int
    {
        if (! $this->isRunning()) {
            return -1;
        }

        return $this->status()
            ->pid();
    }

    /**
     * @return resource
     */
    public function resource(): mixed
    {
        return $this->resource;
    }

    public function start(): self
    {
        return $this;
    }

    public function status(): StatusInterface
    {
        if (! $this->isRunning()) {
            throw new ProcessIsNotRunningException();
        }

        return Status::new($this->resource);
    }

    public function stderr(): StreamInterface
    {
        return $this->stderr;
    }

    public function stdin(): StreamInterface
    {
        return $this->stdin;
    }

    public function stdout(): StreamInterface
    {
        return $this->stdout;
    }

    public function stop(): self
    {
        if (! $this->isRunning()) {
            return $this;
        }

        return $this->terminate();
    }

    public function terminate(int $signal = 15): self
    {
        if (! $this->isRunning()) {
            return $this;
        }

        if (! proc_terminate($this->resource, $signal)) {
            throw new FailedToTerminateProcessException();
        }

        return $this;
    }

    public function wait(): self
    {
        return $this;
    }

    /**
     * @param null|array<string,string> $environmentVariables
     */
    public static function new(
        CommandInterface $command,
        WorkingDirectoryInterface $workingDirectory,
        EnvironmentVariablesInterface $environmentVariables,
    ): self {
        /** @var array<int,resource> $pipes */
        $pipes = [];

        set_error_handler(
            static function (int $severity, string $message): void {
                throw new FailedToExecuteCommandException($message, $severity);
            }
        );

        try {
            $resource = proc_open(
                $command->toArray(),
                self::DESCRIPTORS,
                $pipes,
                $workingDirectory->toString(),
                $environmentVariables->toArray()
            );
        } finally {
            restore_error_handler();
        }

        if ($resource === false) {
            throw new FailedToOpenProcessException();
        }

        return new self(
            command: $command,
            resource: $resource,
            stdin: Stream::new($pipes[0]),
            stdout: Stream::new($pipes[1]),
            stderr: Stream::new($pipes[2]),
        );
    }
}
