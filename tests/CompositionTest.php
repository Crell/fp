<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CompositionTest extends TestCase
{
    #[Test]
    public function basic(): void
    {
        $c = compose(
            fn(string $s): int => strlen($s),
            fn(int $i): int => $i * 2,
        );

        self::assertEquals(10, $c('hello'));
    }

    #[Test]
    public function nested(): void
    {
        $c = compose(
            ...[
                ...[
                    fn(string $s): int => strlen($s),
                    fn(int $i): int => $i * 2,
                ],
                fn(int $i): int => $i * 3,
            ]
        );

        self::assertEquals(30, $c('hello'));
    }

    #[Test]
    public function pipe(): void
    {
        $result = pipe(
            'hello',
            fn(string $s): int => strlen($s),
            fn(int $i): int => $i * 2,
            fn(int $i): int => $i * 3,
        );

        self::assertEquals(30, $result);
    }

    #[Test]
    public function pipe_splat(): void
    {
        $result = pipe(
            'hello',
            ...[
                fn(string $s): int => strlen($s),
                fn(int $i): int => $i * 2,
                fn(int $i): int => $i * 3,
            ]
        );

        self::assertEquals(30, $result);
    }

    #[Test]
    public function pipe_array(): void
    {
        $result = pipe(
            ['hello', 'wide', 'world', 'out', 'there'],
            amap(fn(string $s) => \strlen($s)),
            afilter(fn(int $x): bool => (bool)($x % 2)), // Keep odd numbers
            reduce(0, fn(int $collect, int $x) => $x + $collect),
        );

        self::assertEquals(18, $result);
    }

    #[Test, DataProvider('maybeExamples')]
    public function maybe(?int $val, ?int $expected): void
    {
        $fn = static fn (int $x) => $x + 1;

        $result = maybe($fn)($val);

        self::assertEquals($expected, $result);
    }

    public static function maybeExamples(): iterable
    {
        yield [1, 2];
        yield [null, null];
    }
}
