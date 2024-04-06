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

    /**
     * @param closed-resource|resource $stream
     */
    public function __construct(
        private CommandInterface $command,
        private EnvironmentVariablesInterface $environmentVariables,
        private StdioInterface $stdio,
        private mixed $stream,
        private WorkingDirectoryInterface $workingDirectory,
    ) {
    }

    public function __destruct()
    {
        $this->stdio->close();

        if (! is_resource($this->stream)) {
            return;
        }

        if (! proc_terminate($this->stream, $signal)) {
            throw new FailedToTerminateProcessException();
        }
    }

    public function close(): int
    {
        if (! is_resource($this->stream)) {
            throw new ProcessIsNotRunningException();
        }

        return proc_close($this->stream);
    }

    public function command(): CommandInterface
    {
        return $this->command;
    }

    public function environmentVariables(): EnvironmentVariablesInterface
    {
        return $this->environmentVariables;
    }

    public function stdio(): StdioInterface
    {
        return $this->stdio;
    }

    public function workingDirectory(): WorkingDirectoryInterface
    {
        return $this->workingDirectory;
    }

    public static function new(
        CommandInterface $command,
        WorkingDirectoryInterface $workingDirectory,
        EnvironmentVariablesInterface $environmentVariables,
    ): self {
        set_error_handler(
            static function (int $severity, string $message): void {
                throw new FailedToExecuteCommandException($message, $severity);
            }
        );

        /** @var array{0:resource,1:resource,2:resource} $pipes */
        $pipes = [];

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

        if ($stream === false) {
            throw new FailedToOpenProcessException();
        }

        return new self(
            command: $command,
            environmentVariables: $environmentVariables,
            stdio: Stdio::new($pipes),
            stream: $stream,
            workingDirectory: $workingDirectory,
        );
    }
}
