<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface ProcessInterface
{
    //    public function open(
    //        string $command,
    //        ?string $cwd = null,
    //        ?array $env = null,
    //        ?array $options = null
    //    ): int;

    public function close(): int;

    public function command(): CommandInterface;

    //    public function isRunning(): bool;
    //
    //    public function pid(): int;
    //
    //    public function start(): self;
    //
    //    public function stop(): self;
    //
    //    public function terminate(): self;
    //
    //    public function wait(): self;

    /**
     * @return resource
     */
    public function resource(): mixed;

    public function status(): StatusInterface;

    public function stderr(): StreamInterface;

    public function stdin(): StreamInterface;

    public function stdout(): StreamInterface;
}
