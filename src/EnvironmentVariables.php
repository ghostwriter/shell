<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Interface\EnvironmentVariablesInterface;

use function getenv;

final readonly class EnvironmentVariables implements EnvironmentVariablesInterface
{
    /**
     * @param array<string,string> $environmentVariables
     */
    public function __construct(
        private array $environmentVariables,
    ) {}

    /**
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return $this->environmentVariables;
    }

    /**
     * @param null|array<string,string> $environmentVariables
     */
    public static function new(?array $environmentVariables = null): self
    {
        return new self($environmentVariables ?? getenv() ?: []);
    }
}
