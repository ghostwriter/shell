<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit\Exception;

use Ghostwriter\Shell\Exception\PcntlExtensionNotAvailableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PcntlExtensionNotAvailableException::class)]
final class PcntlExtensionNotAvailableExceptionTest extends TestCase
{
}
