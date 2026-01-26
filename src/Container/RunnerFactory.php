<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Container;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Shell\Runner;
use Ghostwriter\Shell\Task\CloseDescriptorTask;
use Ghostwriter\Shell\Task\ReadDescriptorTask;
use Override;
use Throwable;

/**
 * @see RunnerFactoryTest
 *
 * @implements FactoryInterface<Runner>
 */
final readonly class RunnerFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Runner
    {
        return Runner::new(
            before: $container->get(ReadDescriptorTask::class),
            after: $container->get(CloseDescriptorTask::class),
        );
    }
}
