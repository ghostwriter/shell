<?php

declare(strict_types=1);

namespace Tests\Unit\Task;

use Ghostwriter\Shell\Task\ReadDescriptorTask;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReadDescriptorTask::class)]
final class ReadDescriptorTaskTest extends TestCase
{
    public function testExample(): void
    {
        self::assertTrue(true);
    }
}
