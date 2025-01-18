<?php

declare(strict_types=1);

namespace Tests\Unit\Task;

use Ghostwriter\Shell\Task\CloseDescriptorTask;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CloseDescriptorTask::class)]
final class CloseDescriptorTaskTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
