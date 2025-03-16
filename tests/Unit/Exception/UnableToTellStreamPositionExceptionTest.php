<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Shell\Exception\UnableToTellStreamPositionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UnableToTellStreamPositionException::class)]
final class UnableToTellStreamPositionExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
