<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Container;

use Ghostwriter\Container\Interface\BuilderInterface;
use Ghostwriter\Container\Service\Provider\AbstractProvider;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\ShellInterface;
use Ghostwriter\Shell\Runner;
use Ghostwriter\Shell\Shell;
use Override;
use Throwable;

/**
 * @see ShellProviderTest
 */
final class ShellProvider extends AbstractProvider
{
    /** @throws Throwable */
    #[Override]
    public function register(BuilderInterface $builder): void
    {
        $builder->alias(ShellInterface::class, Shell::class);
        $builder->alias(RunnerInterface::class, Runner::class);
        $builder->factory(Runner::class, RunnerFactory::class);
    }
}
