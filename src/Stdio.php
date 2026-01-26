<?php

declare(strict_types=1);

namespace Ghostwriter\Shell;

use Ghostwriter\Shell\Descriptor\Stderr;
use Ghostwriter\Shell\Descriptor\Stdin;
use Ghostwriter\Shell\Descriptor\Stdout;
use Ghostwriter\Shell\Interface\Stdio\StderrInterface;
use Ghostwriter\Shell\Interface\Stdio\StdinInterface;
use Ghostwriter\Shell\Interface\Stdio\StdoutInterface;
use Ghostwriter\Shell\Interface\StdioInterface;
use Override;
use Throwable;

use const PHP_EOL;

final readonly class Stdio implements StdioInterface
{
    public function __construct(
        private StdinInterface $stdin,
        private StdoutInterface $stdout,
        private StderrInterface $stderr,
    ) {}

    /**
     * @param array{0:resource,1:resource,2:resource} $pipes
     *
     * @throws Throwable
     */
    public static function new(array $pipes): self
    {
        return new self(
            stdin: Stdin::new($pipes[0]),
            stdout: Stdout::new($pipes[1]),
            stderr: Stderr::new($pipes[2]),
        );
    }

    /** @throws Throwable */
    public function __destruct()
    {
        $this->close();
    }

    /** @throws Throwable */
    #[Override]
    public function close(): void
    {
        $this->stdin->close();
        $this->stdout->close();
        $this->stderr->close();
    }

    /** @throws Throwable */
    #[Override]
    public function read(int $length = 4096): string
    {
        return $this->stdin->read($length);
    }

    /** @throws Throwable */
    #[Override]
    public function readLine(): string
    {
        return $this->stdin->readLine();
    }

    /** @throws Throwable */
    #[Override]
    public function stderr(): StderrInterface
    {
        return $this->stderr;
    }

    #[Override]
    public function stdin(): StdinInterface
    {
        return $this->stdin;
    }

    #[Override]
    public function stdout(): StdoutInterface
    {
        return $this->stdout;
    }

    /** @throws Throwable */
    #[Override]
    public function write(string $string): int
    {
        return $this->stdout->write($string);
    }

    /** @throws Throwable */
    #[Override]
    public function writeError(string $string): int
    {
        return $this->stderr->write($string);
    }

    /** @throws Throwable */
    #[Override]
    public function writeErrorLine(string $string): int
    {
        return $this->stderr->write($string . PHP_EOL);
    }

    /** @throws Throwable */
    #[Override]
    public function writeLine(string $string): int
    {
        return $this->stdout->write($string . PHP_EOL);
    }
}
