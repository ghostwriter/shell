<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit;

use Generator;
use Ghostwriter\Shell\EnvironmentVariables;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function getenv;
use function putenv;

#[CoversClass(EnvironmentVariables::class)]
final class EnvironmentVariablesTest extends TestCase
{
    /**
     * @return Generator<array{0:list{string,string}}>
     */
    public static function dataProvider(): Generator
    {
        $key = 'BLM';
        $value = 'BlackLivesMatter';

        putenv("{$key}={$value}");
        $_ENV[$key] = $_SERVER[$key] = $value;

        yield from [
            '$_ENV' => [$_ENV],
            '$_SERVER' => [$_SERVER],
            'empty' => [[]],
            'custom' => [[
                $key=> $value,
            ]],
            'getenv()' => [getenv()],
        ];

        putenv("{$key}");
        unset($_ENV[$key], $_SERVER[$key]);
    }

    /**
     * @param array<string,string> $variables
     */
    #[DataProvider('dataProvider')]
    public static function testNew(array $variables): void
    {
        $environmentVariables = EnvironmentVariables::new($variables);

        self::assertSame($variables, $environmentVariables->toArray());
    }
}
