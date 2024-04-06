<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\ProcOpenFunctionNotAvailableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProcOpenFunctionNotAvailableException::class)]
final class ProcOpenFunctionNotAvailableExceptionTest extends TestCase
{
}
