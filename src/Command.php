<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\CommandArgumentCannotBeEmptyException;
use Ghostwriter\Shell\Exception\CommandNameCannotBeEmptyException;
use Ghostwriter\Shell\Exception\NullPointerException;
use Ghostwriter\Shell\Interface\CommandInterface;
use Override;

use function str_contains;
use function trim;

final readonly class Command implements CommandInterface
{
    /**
     * @param list<string> $arguments
     *
     * @throws CommandNameCannotBeEmptyException
     * @throws CommandArgumentCannotBeEmptyException
     * @throws NullPointerException
     */
    public function __construct(
        private string $name,
        private array $arguments
    ) {
        if (trim($name) === '') {
            throw new CommandNameCannotBeEmptyException();
        }

        foreach ($arguments as $argument) {
            if (str_contains($argument, "\0")) {
                throw new NullPointerException();
            }

            if (trim($argument) === '') {
                throw new CommandArgumentCannotBeEmptyException();
            }
        }
    }

    /**
     * @return list<string>
     */
    #[Override]
    public function arguments(): array
    {
        return $this->arguments;
    }

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return list<string>
     */
    #[Override]
    public function toArray(): array
    {
        return [$this->name, ...$this->arguments];
    }

    /**
     * @param list<string> $arguments
     *
     * @throws CommandNameCannotBeEmptyException
     * @throws CommandArgumentCannotBeEmptyException
     * @throws NullPointerException
     */
    public static function new(string $command, array $arguments = []): self
    {
        return new self($command, $arguments);
    }
}
