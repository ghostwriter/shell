<?php

declare(strict_types=1);

namespace Ghostwriter\Shell\Descriptor;

use Ghostwriter\Shell\Interface\Stdio\StdinInterface;
use Ghostwriter\Shell\Trait\DescriptorTrait;

final class Stdin implements StdinInterface
{
    use DescriptorTrait;
}
