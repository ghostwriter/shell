<?php

declare(strict_types=1);

namespace Tests\Unit\Task;

use Ghostwriter\Shell\Task\NoopTask;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NoopTask::class)]
final class NoopTaskTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
