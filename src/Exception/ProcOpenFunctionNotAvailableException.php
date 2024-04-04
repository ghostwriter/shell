<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Exception;

use Ghostwriter\Shell\Interface\ExceptionInterface;
use RuntimeException;

final class ProcOpenFunctionNotAvailableException extends RuntimeException implements ExceptionInterface
{
}
