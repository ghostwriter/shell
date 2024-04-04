<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

interface ShellInterface
{
    /**
     * @param list<string>              $arguments
     * @param null|array<string,string> $environmentVariables
     */
    public function execute(
        string $command,
        array $arguments = [],
        ?string $workingDirectory = null,
        ?array $environmentVariables = null,
        ?string $stdin = null,
    ): ResultInterface;
}
