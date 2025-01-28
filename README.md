# Shell

[![Automation](https://github.com/ghostwriter/shell/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/shell/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/shell?color=8892bf)](https://www.php.net/supported-versions)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/shell&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/shell?color=blue)](https://packagist.org/packages/ghostwriter/shell)

Execute commands and external programs

> [!WARNING]
> This project is not finished yet, work in progress.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/shell
```

### Star ⭐️ this repo if you find it useful

You can also star (🌟) this repo to find it easier later.

## Usage

```php
$shell = Shell::new();

$shell->execute('cd', [sys_get_temp_dir()]);

$result = $shell->execute(PHP_BINARY, ['-r', 'echo "#BlackLivesMatter";']);

$exitCode = $result->exitCode(); // 0
if ($exitCode !== 0) {
    throw new RuntimeException($result->stderr());
}

if ($exitCode === 0) {
    echo $result->stdout(); // "#BlackLivesMatter"
}
```

### Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/shell/contributors)

### Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

### License

Please see [LICENSE](./LICENSE) for more information on the license that applies to this project.

### Security

Please see [SECURITY.md](./SECURITY.md) for more information on security disclosure process.
