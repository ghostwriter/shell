<?php

declare(strict_types=1);

namespace Tests\Unit\Descriptor;

use Ghostwriter\Shell\Descriptor\Stdin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Stdin::class)]
final class StdinTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
