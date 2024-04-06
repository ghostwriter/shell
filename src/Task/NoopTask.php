<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Task;

use Fiber;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Ghostwriter\Shell\Interface\TaskInterface;
use Throwable;

final class NoopTask implements TaskInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(DescriptorInterface $descriptor): void
    {
        Fiber::suspend();
    }
}
