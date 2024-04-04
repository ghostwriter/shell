<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit;

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
use Ghostwriter\Shell\Stream;
use Ghostwriter\Shell\WorkingDirectory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Throwable;

use const PHP_BINARY;

use function func_get_args;
use function getcwd;
use function putenv;
use function sys_get_temp_dir;

#[CoversClass(Command::class)]
#[CoversClass(EnvironmentVariables::class)]
#[CoversClass(Result::class)]
#[CoversClass(Process::class)]
#[CoversClass(Runner::class)]
#[CoversClass(Shell::class)]
#[CoversClass(Status::class)]
#[CoversClass(Stream::class)]
#[CoversClass(WorkingDirectory::class)]
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
    public function exitCode(): int
    {
        return $this->execute(...func_get_args())
            ->exitCode();
    }

    /**
     * @throws Throwable
     */
    public function stderr(): string
    {
        return $this->execute(...func_get_args())
            ->stderr();
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
    public function testCurrentWorkingDirectory(): void
    {
        $cwd = getcwd();
        self::assertNotEmpty($cwd);
        self::assertSame($cwd, $this->stdout(PHP_BINARY, ['-r', 'echo getcwd();']));
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
     * @throws Throwable
     */
    public function testEnvironmentVariablesViaParameter(): void
    {
        self::assertSame(
            'TRUE',
            $this->stdout(PHP_BINARY, ['-r', 'echo getenv("BLM");'], null, [
                'BLM' => 'TRUE',
            ])
        );
    }

    /**
     * @throws Throwable
     */
    public function testExecute(): void
    {
        $result = $this->execute(PHP_BINARY, ['-r', 'echo "#BlackLivesMatter";']);

        self::assertSame(0, $result->exitCode());

        self::assertSame('#BlackLivesMatter', $result->stdout());

        self::assertEmpty($result->stderr());
    }

    /**
     * @throws Throwable
     */
    public function testFailedExecution(): void
    {
        $result = $this->execute(PHP_BINARY, ['-r', 'blackLivesMatter("#BLM!");']);

        self::assertSame(255, $result->exitCode());
        self::assertStringContainsString('Call to undefined function blackLivesMatter()', $result->stderr());
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
}
