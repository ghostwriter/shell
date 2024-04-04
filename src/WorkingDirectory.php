<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\InvalidWorkingDirectoryException;
use Ghostwriter\Shell\Interface\WorkingDirectoryInterface;

use function getcwd;
use function is_dir;

final readonly class WorkingDirectory implements WorkingDirectoryInterface
{
    public function __construct(
        private string $path,
    ) {
        if (! is_dir($this->path)) {
            throw new InvalidWorkingDirectoryException();
        }
    }

    public function toString(): string
    {
        return $this->path;
    }

    public static function new(?string $workingDirectory = null): self
    {
        return new self($workingDirectory ?? getcwd());
    }
}
