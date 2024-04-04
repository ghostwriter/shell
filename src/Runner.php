<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Fiber;
use Ghostwriter\Shell\Exception\FailedToReadFromPipeException;
use Ghostwriter\Shell\Interface\ProcessInterface;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\StreamInterface;
use SplQueue;

final readonly class Runner implements RunnerInterface
{
    public function run(ProcessInterface $process): ResultInterface
    {
        $streamReadClose = static function (StreamInterface $stream): void {
            Fiber::suspend();

            $output = false;
            while (! $stream->endOfFile()) {
                try {
                    $output = $stream->read(StreamInterface::READABLE_BYTES);
                } catch (FailedToReadFromPipeException) {
                    $stream->close();
                    return;
                }

                Fiber::suspend();
            }
            $stream->close();

            if ($output === false) {
                throw new FailedToReadFromPipeException();
            }
        };

        $fiberOutput = new Fiber($streamReadClose);
        $fiberErrorOutput = new Fiber($streamReadClose);

        $fiberOutput->start($process->stdout());
        $fiberErrorOutput->start($process->stderr());

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

        Status::new($process->resource());

        return Result::new($process);
    }
}
