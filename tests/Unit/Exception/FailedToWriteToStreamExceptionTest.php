<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToWriteToStreamException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToWriteToStreamException::class)]
final class FailedToWriteToStreamExceptionTest extends TestCase
{
}
