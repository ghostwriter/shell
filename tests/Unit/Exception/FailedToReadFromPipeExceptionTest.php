<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToReadFromPipeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToReadFromPipeException::class)]
final class FailedToReadFromPipeExceptionTest extends TestCase
{
}
