<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Exception\PcntlExtensionNotAvailableException;
use Ghostwriter\Shell\Exception\ProcOpenFunctionIsDisabledException;
use Ghostwriter\Shell\Exception\ProcOpenFunctionNotAvailableException;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\ShellInterface;
use Ghostwriter\Shell\Interface\Stdio\StdinInterface;
use Ghostwriter\Shell\Task\CloseDescriptorTask;
use Ghostwriter\Shell\Task\ReadDescriptorTask;
use Override;
use Throwable;

use function explode;
use function extension_loaded;
use function function_exists;
use function in_array;
use function ini_get;

/** @see ShellTest */
final readonly class Shell implements ShellInterface
{
    /**
     * @throws PcntlExtensionNotAvailableException
     * @throws ProcOpenFunctionIsDisabledException
     * @throws ProcOpenFunctionNotAvailableException
     */
    public function __construct(
        private RunnerInterface $runner,
    ) {
        if (! extension_loaded('pcntl')) {
            throw new PcntlExtensionNotAvailableException();
        }

        if (! function_exists('proc_open')) {
            throw new ProcOpenFunctionNotAvailableException();
        }

        $disableFunctions = ini_get('disable_functions');

        if ($disableFunctions !== false) {
            $disabledFunctions = explode(',', $disableFunctions);

            if (in_array('proc_open', $disabledFunctions, true)) {
                throw new ProcOpenFunctionIsDisabledException();
            }
        }
    }

    public function __destruct()
    {
    }

    /**
     * @param list<string>              $arguments
     * @param null|array<string,string> $environmentVariables
     *
     * @throws Throwable
     */
    #[Override]
    public function execute(
        string $command,
        array $arguments = [],
        ?string $workingDirectory = null,
        ?array $environmentVariables = null,
        ?string $input = null,
    ): ResultInterface {
        $process = Process::new(
            Command::new($command, $arguments),
            WorkingDirectory::new($workingDirectory),
            EnvironmentVariables::new($environmentVariables)
        );

        if ($input !== null) {
            (static function (StdinInterface $stdin, string $input): void {
                $stdin->write($input);
                $stdin->close();
            })($process->stdio()->stdin(), $input);
        }

        return $this->runner->run($process);
    }

    /**
     * @throws PcntlExtensionNotAvailableException
     * @throws ProcOpenFunctionIsDisabledException
     * @throws ProcOpenFunctionNotAvailableException
     */
    public static function new(): self
    {
        return new self(Runner::new(before: new ReadDescriptorTask(), after: new CloseDescriptorTask()));
    }
}
