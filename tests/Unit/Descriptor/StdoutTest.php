<?php

declare(strict_types=1);

namespace Tests\Unit\Descriptor;

use Ghostwriter\Shell\Descriptor\Stdout;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Stdout::class)]
final class StdoutTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
