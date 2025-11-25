<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface CommandInterface
{
    /** @return list<string> */
    public function arguments(): array;

    public function name(): string;

    /** @return list<string> */
    public function toArray(): array;
}
