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
use Ghostwriter\Shell\Interface\StdioInterface;
use Ghostwriter\Shell\Interface\WorkingDirectoryInterface;
use Override;
use Throwable;

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
    public const array DESCRIPTORS = [
        0 => ['pipe', 'r+b'], // stdin
        1 => ['pipe', 'w+b'], // stdout
        2 => ['pipe', 'w+b'], // stderr
    ];

    /** @var array{0:resource,1:resource,2:resource} */
    public const array PIPES = [];

    /**
     * @param closed-resource|resource $stream
     */
    public function __construct(
        private CommandInterface $command,
        private EnvironmentVariablesInterface $environmentVariables,
        private StdioInterface $stdio,
        private mixed $stream,
        private WorkingDirectoryInterface $workingDirectory,
    ) {}

    /**
     * @throws Throwable
     */
    public static function new(
        CommandInterface $command,
        WorkingDirectoryInterface $workingDirectory,
        EnvironmentVariablesInterface $environmentVariables,
    ): self {
        set_error_handler(
            static function (int $severity, string $message): never {
                throw new FailedToExecuteCommandException($message, $severity);
            }
        );

        $pipes = self::PIPES;

        try {
            $stream = proc_open(
                $command->toArray(),
                self::DESCRIPTORS,
                $pipes,
                $workingDirectory->toString(),
                $environmentVariables->toArray()
            );
        } finally {
            restore_error_handler();
        }

        return false === $stream ? throw new FailedToOpenProcessException() : new self(
            command: $command,
            environmentVariables: $environmentVariables,
            stdio: Stdio::new($pipes),
            stream: $stream,
            workingDirectory: $workingDirectory,
        );
    }

    /**
     * @throws FailedToTerminateProcessException
     */
    public function __destruct()
    {
        $this->stdio->close();

        if (! is_resource($this->stream)) {
            return;
        }

        $signal = 15;
        if (! proc_terminate($this->stream, $signal)) {
            throw new FailedToTerminateProcessException();
        }
    }

    /**
     * @throws ProcessIsNotRunningException
     */
    #[Override]
    public function close(): int
    {
        if (! is_resource($this->stream)) {
            throw new ProcessIsNotRunningException();
        }

        return proc_close($this->stream);
    }

    #[Override]
    public function command(): CommandInterface
    {
        return $this->command;
    }

    #[Override]
    public function environmentVariables(): EnvironmentVariablesInterface
    {
        return $this->environmentVariables;
    }

    #[Override]
    public function stdio(): StdioInterface
    {
        return $this->stdio;
    }

    #[Override]
    public function workingDirectory(): WorkingDirectoryInterface
    {
        return $this->workingDirectory;
    }
}
