<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\InvalidLengthException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidLengthException::class)]
final class InvalidLengthExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
