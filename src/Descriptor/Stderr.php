<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Descriptor;

use Ghostwriter\Shell\Interface\Stdio\StderrInterface;
use Ghostwriter\Shell\Trait\DescriptorTrait;

final class Stderr implements StderrInterface
{
    use DescriptorTrait;
}
