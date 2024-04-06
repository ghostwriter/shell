<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToReadFromStreamException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToReadFromStreamException::class)]
final class FailedToReadFromStreamExceptionTest extends TestCase
{
}
