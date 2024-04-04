<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Exception;

use Ghostwriter\Shell\Interface\ExceptionInterface;
use RuntimeException;

final class UnableToWriteToStreamException extends RuntimeException implements ExceptionInterface
{
}
