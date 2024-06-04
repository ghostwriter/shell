<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToSetStreamBlockingException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToSetStreamBlockingException::class)]
final class FailedToSetStreamBlockingExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
