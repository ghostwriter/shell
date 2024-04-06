<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Interface;

use Ghostwriter\Shell\Interface\Stdio\StderrInterface;
use Ghostwriter\Shell\Interface\Stdio\StdinInterface;
use Ghostwriter\Shell\Interface\Stdio\StdoutInterface;

interface StdioInterface
{
    public function close(): void;

    public function read(int $length): string;

    public function readLine(): string;

    public function stderr(): StderrInterface;

    public function stdin(): StdinInterface;

    public function stdout(): StdoutInterface;

    public function write(string $string): void;

    public function writeError(string $string): void;

    public function writeErrorLine(string $string): void;

    public function writeLine(string $string): void;
}
