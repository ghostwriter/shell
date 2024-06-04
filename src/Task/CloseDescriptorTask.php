<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Task;

use Fiber;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Ghostwriter\Shell\Interface\TaskInterface;
use Throwable;
use Override;

final class CloseDescriptorTask implements TaskInterface
{
    /**
     * @throws Throwable
     */
    #[Override]
    public function __invoke(DescriptorInterface $descriptor): void
    {
        do {
            Fiber::suspend();

            $descriptor->close();
        } while ($descriptor->isRunning());
    }
}
