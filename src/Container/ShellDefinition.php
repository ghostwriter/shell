<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Container;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\DefinitionInterface;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\ShellInterface;
use Ghostwriter\Shell\Runner;
use Ghostwriter\Shell\Shell;
use Override;
use Throwable;

/**
 * @see ShellDefinitionTest
 */
final readonly class ShellDefinition implements DefinitionInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): void
    {
        $container->alias(Shell::class, ShellInterface::class);
        $container->alias(Runner::class, RunnerInterface::class);
        $container->factory(Runner::class, RunnerFactory::class);
    }
}
