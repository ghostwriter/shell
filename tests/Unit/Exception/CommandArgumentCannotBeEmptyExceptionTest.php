<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\CommandArgumentCannotBeEmptyException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CommandArgumentCannotBeEmptyException::class)]
final class CommandArgumentCannotBeEmptyExceptionTest extends TestCase
{
}
