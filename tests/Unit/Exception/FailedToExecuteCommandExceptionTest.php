<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToExecuteCommandException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToExecuteCommandException::class)]
final class FailedToExecuteCommandExceptionTest extends TestCase
{
}
