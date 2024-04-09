<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\UnableToSeekInStreamException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UnableToSeekInStreamException::class)]
final class UnableToSeekInStreamExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
