<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Shell\Exception\StreamIsNotReadableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StreamIsNotReadableException::class)]
final class StreamIsNotReadableExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
