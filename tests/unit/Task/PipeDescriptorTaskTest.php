<?php

declare(strict_types=1);

namespace Tests\Unit\Task;

use Ghostwriter\Shell\Task\PipeDescriptorTask;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PipeDescriptorTask::class)]
final class PipeDescriptorTaskTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
