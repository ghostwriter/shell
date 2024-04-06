<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\StreamIsNotSeekableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StreamIsNotSeekableException::class)]
final class StreamIsNotSeekableExceptionTest extends TestCase
{
}
