<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface EnvironmentVariablesInterface
{
    /** @return array<string,string> */
    public function toArray(): array;
}
