<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\Shell\Command;
use Ghostwriter\Shell\EnvironmentVariables;
use Ghostwriter\Shell\Exception\CommandArgumentCannotBeEmptyException;
use Ghostwriter\Shell\Exception\InvalidWorkingDirectoryException;
use Ghostwriter\Shell\Exception\NullPointerException;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Process;
use Ghostwriter\Shell\Result;
use Ghostwriter\Shell\Runner;
use Ghostwriter\Shell\Shell;
use Ghostwriter\Shell\Status;
use Ghostwriter\Shell\Stdio;
use Ghostwriter\Shell\StringBuffer;
use Ghostwriter\Shell\Task\CloseDescriptorTask;
use Ghostwriter\Shell\Task\ReadDescriptorTask;
use Ghostwriter\Shell\Trait\DescriptorTrait;
use Ghostwriter\Shell\WorkingDirectory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

use const PHP_BINARY;

use function func_get_args;
use function getcwd;
use function putenv;
use function sys_get_temp_dir;

#[CoversClass(CloseDescriptorTask::class)]
#[CoversClass(Command::class)]
#[CoversClass(EnvironmentVariables::class)]
#[CoversClass(Process::class)]
#[CoversClass(ReadDescriptorTask::class)]
#[CoversClass(Result::class)]
#[CoversClass(Runner::class)]
#[CoversClass(Shell::class)]
#[CoversClass(Status::class)]
#[CoversClass(Stdio::class)]
#[CoversClass(StringBuffer::class)]
#[CoversClass(WorkingDirectory::class)]
#[CoversTrait(DescriptorTrait::class)]
final class ShellTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function execute(): ResultInterface
    {
        return Shell::new()->execute(...func_get_args());
    }

    /**
     * @throws Throwable
     */
    public function stdout(): string
    {
        return $this->execute(...func_get_args())
            ->stdout();
    }

    /**
     * @throws Throwable
     */
    public function testEnvironmentVariables(): void
    {
        try {
            putenv('BLACK_LIVES_MATTER=TRUE');

            self::assertSame('TRUE', $this->stdout(PHP_BINARY, ['-r', 'echo getenv("BLACK_LIVES_MATTER");']));
        } finally {
            putenv('BLACK_LIVES_MATTER=');
        }
    }

    /**
     * @param list<string>              $arguments
     * @param null|array<string,string> $environmentVariables
     *
     * @throws Throwable
     */
    #[DataProvider('executeDataProvider')]
    public function testExecute(
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?array $environmentVariables = null,
        ?string $input = null,
        int $exitCode = -9999,
        string $stdout = '',
        string $stderr = ''
    ): void {
        $result = Shell::new()
            ->execute($command, $arguments, $workingDirectory, $environmentVariables, $input);

        self::assertSame($exitCode, $result->exitCode());

        self::assertSame($stdout, $result->stdout());

        self::assertSame($stderr, $result->stderr());
    }

    /**
     * @throws Throwable
     */
    public function testFailedExecution(): void
    {
        $expected = 'Uncaught Error: Call to undefined function blackLivesMatter() in Command line code';

        $result = $this->execute(PHP_BINARY, ['-r', 'blackLivesMatter("#BLM!");']);

        self::assertSame(255, $result->exitCode());

        self::assertStringContainsString($expected, $result->stdout());

        self::assertStringContainsString($expected, $result->stderr());
    }

    /**
     * @throws Throwable
     */
    public function testStderr(): void
    {
        $result = $this->execute(
            PHP_BINARY,
            ['-r', 'fwrite(STDOUT, "#BLM"); fwrite(STDERR, "#BlackLivesMatter");']
        );

        self::assertSame('#BLM', $result->stdout());
        self::assertSame('#BlackLivesMatter', $result->stderr());
    }

    /**
     * @throws Throwable
     */
    public function testThrowsCommandArgumentCannotBeEmptyException(): void
    {
        $this->expectException(CommandArgumentCannotBeEmptyException::class);

        $this->execute(PHP_BINARY, ['']);
    }

    /**
     * @throws Throwable
     */
    public function testThrowsCommandArgumentCannotBeEmptySpaceException(): void
    {
        $this->expectException(CommandArgumentCannotBeEmptyException::class);

        $this->execute(PHP_BINARY, [' ']);
    }

    /**
     * @throws Throwable
     */
    public function testThrowsInvalidWorkingDirectoryException(): void
    {
        $dir = __DIR__ . '/path-not-found/';

        $this->expectException(InvalidWorkingDirectoryException::class);

        $this->execute(PHP_BINARY, ['-r', 'echo getcwd();'], $dir);
    }

    /**
     * @throws Throwable
     */
    public function testThrowsNullPointerException(): void
    {
        $this->expectException(NullPointerException::class);

        $this->execute(PHP_BINARY, ["\0"]);
    }

    /**
     * @throws Throwable
     */
    public function testWorkingDirectoryIsUsed(): void
    {
        $temp = sys_get_temp_dir();

        $result = $this->execute(PHP_BINARY, ['-r', 'echo getcwd();'], $temp);

        self::assertStringEndsWith($temp, $result->stdout());
    }

    public static function executeDataProvider(): Generator
    {
        // $command, $arguments, $workingDirectory, $environmentVariables, $input, $exitCode, $stdout, $stderr
        $command = PHP_BINARY;
        $workingDirectory = getcwd();
        $environmentVariables = null;
        $input = null;
        yield from [
            '#BLM' => [
                $command,
                ['-r', 'echo "#BLM";'],
                $workingDirectory,
                $environmentVariables,
                $input,
                0,
                '#BLM',
                '',
            ],
            '#BlackLivesMatter' => [
                $command,
                ['-r', 'echo "#BlackLivesMatter";'],
                $workingDirectory,
                $environmentVariables,
                $input,
                0,
                '#BlackLivesMatter',
                '',
            ],

            'current working directory' => [
                $command,
                ['-r', 'echo getcwd();'],
                $workingDirectory,
                $environmentVariables,
                $input,
                0,
                $workingDirectory,
                '',
            ],

            'provide environment variables' => [
                $command,
                ['-r', 'echo getenv("BLM");'],
                $workingDirectory,
                [
                    'BLM' => 'TRUE',
                ],
                $input,
                0,
                'TRUE',
                '',
            ],

            'stdout + stderr' => [
                $command,
                ['-r', 'fwrite(STDOUT, "#BLM"); fwrite(STDERR, "#BlackLivesMatter");'],
                $workingDirectory,
                [
                    'BLM' => 'TRUE',
                ],
                $input,
                0,
                '#BLM',
                '#BlackLivesMatter',
            ],
        ];
    }
}
