<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Shell\StringBuffer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringBuffer::class)]
final class StringBufferTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
