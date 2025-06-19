<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\ProcessStoppedViaSignalException;
use Ghostwriter\Shell\Exception\ProcessTerminatedViaSignalException;
use Ghostwriter\Shell\Interface\StatusInterface;
use Override;

use function proc_get_status;

final readonly class Status implements StatusInterface
{
    /**
     * @throws ProcessStoppedViaSignalException
     * @throws ProcessTerminatedViaSignalException
     */
    public function __construct(
        public string $command,
        public int $exitCode,
        public int $pid,
        public bool $running,
        public bool $signaled,
        public bool $stopped,
        public int $stopSignal,
        public int $terminateSignal
    ) {
        if ($this->signaled) {
            throw new ProcessTerminatedViaSignalException(code: $this->terminateSignal);
        }

        if ($this->stopped) {
            throw new ProcessStoppedViaSignalException(code: $this->stopSignal);
        }
    }

    /**
     * @param resource $process
     *
     * @throws ProcessStoppedViaSignalException
     * @throws ProcessTerminatedViaSignalException
     */
    public static function new(mixed $process): self
    {
        $status = proc_get_status($process);

        return new self(
            $status['command'],
            $status['exitcode'],
            $status['pid'],
            $status['running'],
            $status['signaled'], // true -> termsig
            $status['stopped'], // true -> stopsig
            $status['stopsig'],
            $status['termsig']
        );
    }

    #[Override]
    public function command(): string
    {
        return $this->command;
    }

    #[Override]
    public function exitCode(): int
    {
        return $this->exitCode;
    }

    #[Override]
    public function pid(): int
    {
        return $this->pid;
    }

    #[Override]
    public function running(): bool
    {
        return $this->running;
    }

    #[Override]
    public function signaled(): bool
    {
        return $this->signaled;
    }

    #[Override]
    public function stopSignal(): int
    {
        return $this->stopSignal;
    }

    #[Override]
    public function stopped(): bool
    {
        return $this->stopped;
    }

    #[Override]
    public function terminateSignal(): int
    {
        return $this->terminateSignal;
    }
}
