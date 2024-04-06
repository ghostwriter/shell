<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Fiber;
use Ghostwriter\Shell\Interface\ProcessInterface;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\TaskInterface;
use Ghostwriter\Shell\Task\NoopTask;
use SplQueue;

use function array_unshift;

final readonly class Runner implements RunnerInterface
{
    public function __construct(
        private TaskInterface $before,
        private TaskInterface $after
    ) {
    }

    public function run(ProcessInterface $process, TaskInterface ...$tasks): ResultInterface
    {
        array_unshift($tasks, $this->before);

        $tasks[] = $this->after;

        foreach ($tasks as $task) {
            $fiberOutput = new Fiber($task);
            $fiberErrorOutput = new Fiber($task);

            $stdio = $process->stdio();

            $stdout = $stdio->stdout();

            $stderr = $stdio->stderr();

            $fiberOutput->start($stdout);
            $fiberErrorOutput->start($stderr);

            /** @var SplQueue<Fiber> $splQueue */
            $splQueue = new SplQueue();
            $splQueue->enqueue($fiberOutput);
            $splQueue->enqueue($fiberErrorOutput);

            do {
                $fiber = $splQueue->dequeue();

                if (! $fiber instanceof Fiber) {
                    continue;
                }

                $fiber->resume();

                if ($fiber->isTerminated()) {
                    continue;
                }

                $splQueue->enqueue($fiber);
            } while (! $splQueue->isEmpty());
        }

        return Result::new($process->close(), $stdout->toString(), $stderr->toString());
    }

    public static function new(
        TaskInterface $before = new NoopTask(),
        TaskInterface $after = new NoopTask()
    ): self {
        return new self(before: $before, after: $after);
    }
}
