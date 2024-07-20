<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Compliance\Enum\Tool;

return Automation::new()
    ->composerStrategies(...ComposerStrategy::cases())
    ->operatingSystems(...OperatingSystem::cases())
    ->phpVersions(...PhpVersion::cases())
    ->skip(OperatingSystem::WINDOWS)
    ->tools(...Tool::cases());
