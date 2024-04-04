<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

use Throwable;

interface RunnerInterface
{
    /**
     * @throws Throwable
     */
    public function run(ProcessInterface $process): ResultInterface;
}
