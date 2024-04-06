<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\FailedToSetStreamBlockingException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FailedToSetStreamBlockingException::class)]
final class FailedToSetStreamBlockingExceptionTest extends TestCase
{
}
