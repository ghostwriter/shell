<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\ProcessIsNotRunningException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProcessIsNotRunningException::class)]
final class ProcessIsNotRunningExceptionTest extends TestCase
{
}
