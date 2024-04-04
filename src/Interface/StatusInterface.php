<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface StatusInterface
{
    public function command(): string;

    public function exitCode(): int;

    public function pid(): int;

    public function running(): bool;

    public function signaled(): bool;

    public function stopSignal(): int;

    public function stopped(): bool;

    public function terminateSignal(): int;
}
