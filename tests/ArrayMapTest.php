<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ArrayMapTest extends TestCase
{
    #[Test]
    public function itmap(): void
    {
        $result = itmap(fn(int $x): int => $x * 2)([5, 6]);
        self::assertEquals([10, 12], iterator_to_array($result));
    }

    #[Test]
    public function itmap_keys(): void
    {
        $result = itmapWithKeys(fn(int $x, int $k): int => $x * 2 + $k)([5, 6]);
        self::assertEquals([10, 13], iterator_to_array($result));
    }

    #[Test]
    public function itmap_iterator(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = itmap(fn(int $x): int => $x * 2)($gen());
        self::assertEquals([10, 12], iterator_to_array($result));
    }

    #[Test]
    public function itmap_iterator_keys(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = itmapWithKeys(fn(int $x, int $k): int => $x * 2 + $k)($gen());
        self::assertEquals([10, 13], iterator_to_array($result));
    }

    #[Test]
    public function amap(): void
    {
        $result = amap(fn(int $x): int => $x * 2)([5, 6]);
        self::assertEquals([10, 12], $result);
    }

    #[Test]
    public function amap_keys(): void
    {
        $result = amapWithKeys(fn(int $x, int $k): int => $x * 2 + $k)([5, 6]);
        self::assertEquals([10, 13], $result);
    }

    #[Test]
    public function amap_iterator(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = amap(fn(int $x): int => $x * 2)($gen());
        self::assertEquals([10, 12], $result);
    }

    #[Test]
    public function amap_iterator_keys(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = amapWithKeys(fn(int $x, int $k): int => $x * 2 + $k)($gen());
        self::assertEquals([10, 13], $result);
    }

    #[Test]
    public function amapPreserveKeys(): void
    {
        $a = ['a' => 'A', 'b' => 'B', 'c' => 'C'];

        $result = amap(fn(string $x): string => $x . 'hi')($a);

        self::assertEquals(['a' => 'Ahi', 'b' => 'Bhi', 'c' => 'Chi'], $result);
    }

    #[Test]
    public function amapWithKeysPreserveKeys(): void
    {
        $a = ['a' => 'A', 'b' => 'B', 'c' => 'C'];

        $result = amapWithKeys(fn(string $v, string $k): string => $v . $k)($a);

        self::assertEquals(['a' => 'Aa', 'b' => 'Bb', 'c' => 'Cc'], $result);
    }
}
