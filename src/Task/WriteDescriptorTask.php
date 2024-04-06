<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Task;

use Fiber;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Ghostwriter\Shell\Interface\TaskInterface;

final readonly class WriteDescriptorTask implements TaskInterface
{
    public function __construct(
        private string $content
    ) {
    }

    public function __invoke(DescriptorInterface $descriptor): void
    {
        do {
            Fiber::suspend();

            $descriptor->write($this->content);
        } while ($descriptor->isRunning());
    }
}
