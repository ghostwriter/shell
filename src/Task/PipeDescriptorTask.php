<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Task;

use Fiber;
use Ghostwriter\Shell\Exception\FailedToReadFromStreamException;
use Ghostwriter\Shell\Exception\FailedToWriteToStreamException;
use Ghostwriter\Shell\Interface\DescriptorInterface;
use Ghostwriter\Shell\Interface\TaskInterface;

final readonly class PipeDescriptorTask implements TaskInterface
{
    public function __construct(
        private DescriptorInterface $descriptor,
    ) {
    }

    public function __invoke(DescriptorInterface $descriptor): void
    {
        do {
            Fiber::suspend();

            try {
                $descriptor->write($this->descriptor->toString());
            } catch (FailedToReadFromStreamException) {
                continue;
            } catch (FailedToWriteToStreamException) {
                break;
            }
        } while ($descriptor->isRunning());
    }
}
