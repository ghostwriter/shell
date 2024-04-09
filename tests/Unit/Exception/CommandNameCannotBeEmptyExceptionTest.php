<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\CommandNameCannotBeEmptyException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CommandNameCannotBeEmptyException::class)]
final class CommandNameCannotBeEmptyExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
