<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Shell\Process;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Process::class)]
final class ProcessTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
