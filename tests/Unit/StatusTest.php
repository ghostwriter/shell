<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit;

use Ghostwriter\Shell\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Status::class)]
final class StatusTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
