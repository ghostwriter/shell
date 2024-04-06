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

use const PHP_EOL;

final readonly class Stdio implements StdioInterface
{
    public function __construct(
        private StdinInterface $stdin,
        private StdoutInterface $stdout,
        private StderrInterface $stderr,
    ) {
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close(): void
    {
        $this->stdin->close();
        $this->stdout->close();
        $this->stderr->close();
    }

    public function read(int $length = 4096): string
    {
        return $this->stdin->read($length);
    }

    public function readLine(): string
    {
        return $this->stdin->readLine();
    }

    public function stderr(): StderrInterface
    {
        return $this->stderr;
    }

    public function stdin(): StdinInterface
    {
        return $this->stdin;
    }

    public function stdout(): StdoutInterface
    {
        return $this->stdout;
    }

    public function write(string $string): void
    {
        $this->stdout->write($string);
    }

    public function writeError(string $string): void
    {
        $this->stderr->write($string);
    }

    public function writeErrorLine(string $string): void
    {
        $this->stderr->write($string . PHP_EOL);
    }

    public function writeLine(string $string): void
    {
        $this->stdout->write($string . PHP_EOL);
    }

    /**
     * @param array{0:resource,1:resource,2:resource} $pipes
     */
    public static function new(array $pipes): self
    {
        return new self(
            stdin: Stdin::new($pipes[0]),
            stdout: Stdout::new($pipes[1]),
            stderr: Stderr::new($pipes[2]),
        );
    }
}
