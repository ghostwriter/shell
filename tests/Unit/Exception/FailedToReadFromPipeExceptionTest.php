<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToReadFromPipeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToReadFromPipeException::class)]
final class FailedToReadFromPipeExceptionTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
