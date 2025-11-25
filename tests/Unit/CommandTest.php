<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\Shell\Command;
use Ghostwriter\Shell\Exception\CommandArgumentCannotBeEmptyException;
use Ghostwriter\Shell\Exception\CommandNameCannotBeEmptyException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(Command::class)]
final class CommandTest extends TestCase
{
    /**
     * @param list<string>                  $arguments
     * @param list<class-string<Throwable>> $expectedExceptions
     */
    #[DataProvider('provideInvalidCases')]
    public function testInvalid(string $name, array $arguments, array $expectedExceptions): void
    {
        foreach ($expectedExceptions as $expectedException) {
            $this->expectException($expectedException);
        }

        Command::new($name, $arguments);
    }

    /** @param list<string> $arguments */
    #[DataProvider('provideNewCases')]
    public function testNew(string $name, array $arguments): void
    {
        $command = Command::new($name, $arguments);

        self::assertSame($name, $command->name());

        self::assertSame($arguments, $command->arguments());

        self::assertSame([$name, ...$arguments], $command->toArray());
    }

    /** @return Generator<array{0:string,1:list<string>}> */
    public static function provideNewCases(): iterable
    {
        yield from [
            'command' => ['command', ['argument-0', 'argument-1']],
        ];
    }

    /** @return Generator<array{0:string,1:list<string>}> */
    public static function provideInvalidCases(): iterable
    {
        yield from [
            'empty-command' => ['', ['argument-0', 'argument-1'], [CommandNameCannotBeEmptyException::class]],
            'empty-argument' => ['command', ['', 'argument-1'], [CommandArgumentCannotBeEmptyException::class]],
            'empty-space-argument' => ['command', [' ', 'argument-1'], [CommandArgumentCannotBeEmptyException::class]],
        ];
    }
}
