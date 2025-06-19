<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface ProcessInterface
{
    //    public function wait(): self;
    public function close(): int;

    public function command(): CommandInterface;

    public function environmentVariables(): EnvironmentVariablesInterface;

    public function stdio(): StdioInterface;

    public function workingDirectory(): WorkingDirectoryInterface;

    //    public function isRunning(): bool;
    //
    //    public function pid(): int;
    //
    //    public function start(): self;
    //
    //    public function stop(): self;
    //
    //    public function terminate(int $signal = 15): void;
}
