<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit;

use Ghostwriter\Shell\Result;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Result::class)]
final class ResultTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
