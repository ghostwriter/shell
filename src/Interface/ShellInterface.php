<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

use Ghostwriter\Shell\Interface\Exception\ShellExceptionInterface;

interface ShellInterface
{
    /**
     * @param list<string>              $arguments
     * @param null|array<string,string> $environmentVariables
     *
     * @throws ShellExceptionInterface
     */
    public function execute(
        string $command,
        array $arguments = [],
        ?string $workingDirectory = null,
        ?array $environmentVariables = null,
        ?string $input = null,
    ): ResultInterface;
}
