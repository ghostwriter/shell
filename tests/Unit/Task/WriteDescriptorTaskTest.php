<?php

declare(strict_types=1);

namespace Tests\Unit\Task;

use Ghostwriter\Shell\Task\WriteDescriptorTask;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WriteDescriptorTask::class)]
final class WriteDescriptorTaskTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
