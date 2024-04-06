<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Descriptor;

use Ghostwriter\Shell\Interface\Stdio\StdoutInterface;
use Ghostwriter\Shell\Trait\DescriptorTrait;

final class Stdout implements StdoutInterface
{
    use DescriptorTrait;
}
