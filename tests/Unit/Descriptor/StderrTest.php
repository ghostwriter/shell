<?php

declare(strict_types=1);

namespace Tests\Unit\Descriptor;

use Ghostwriter\Shell\Descriptor\Stderr;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Stderr::class)]
final class StderrTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
