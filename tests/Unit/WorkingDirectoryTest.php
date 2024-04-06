<?php

declare(strict_types=1);

namespace Ghostwriter\ShellTests\Unit;

use Generator;
use Ghostwriter\Shell\WorkingDirectory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function getcwd;
use function sys_get_temp_dir;

#[CoversClass(WorkingDirectory::class)]
final class WorkingDirectoryTest extends TestCase
{
    /**
     * @return Generator<array{0:string}>
     */
    public static function dataProvider(): Generator
    {
        yield from [
            'getcwd()' => [getcwd()],
            'sys_get_temp_dir()' => [sys_get_temp_dir()],
        ];
    }

    #[DataProvider('dataProvider')]
    public static function testNew(string $path): void
    {
        $workingDirectory = WorkingDirectory::new($path);
        self::assertSame($path, $workingDirectory->toString());
    }
}
