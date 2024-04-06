<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\StreamIsNotWritableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StreamIsNotWritableException::class)]
final class StreamIsNotWritableExceptionTest extends TestCase
{
}
