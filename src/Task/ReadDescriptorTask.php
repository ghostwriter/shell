<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Task;

use Fiber;
use Ghostwriter\Shell\Exception\FailedToReadFromStreamException;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Ghostwriter\Shell\Interface\TaskInterface;
use Override;

final class ReadDescriptorTask implements TaskInterface
{
    #[Override]
    public function __invoke(DescriptorInterface $descriptor): void
    {
        do {
            Fiber::suspend();

            try {
                $descriptor->read();
            } catch (FailedToReadFromStreamException) {
                break;
            }
        } while ($descriptor->isRunning());
    }
}
