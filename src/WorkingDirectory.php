<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\InvalidWorkingDirectoryException;
use Ghostwriter\Shell\Interface\WorkingDirectoryInterface;
use Override;

use function getcwd;
use function is_dir;

final readonly class WorkingDirectory implements WorkingDirectoryInterface
{
    /**
     * @throws InvalidWorkingDirectoryException
     */
    public function __construct(
        private string $path,
    ) {
        if (! is_dir($this->path)) {
            throw new InvalidWorkingDirectoryException();
        }
    }

    /**
     * @throws InvalidWorkingDirectoryException
     */
    public static function new(?string $workingDirectory = null): self
    {
        return new self(match (true) {
            null === $workingDirectory => getcwd(),
            default => $workingDirectory,
        });
    }

    #[Override]
    public function toString(): string
    {
        return $this->path;
    }
}
