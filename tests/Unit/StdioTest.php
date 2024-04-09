<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit;

use Ghostwriter\Shell\Stdio;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Stdio::class)]
final class StdioTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
