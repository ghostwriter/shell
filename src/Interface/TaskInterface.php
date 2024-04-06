<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

use Throwable;

interface TaskInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(DescriptorInterface $descriptor): void;
}
