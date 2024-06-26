<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Shell\Exception\InvalidStreamResourceException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidStreamResourceException::class)]
final class InvalidStreamResourceExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
