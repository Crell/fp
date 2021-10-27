<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    /**
     * @test
     */
    public function itmap(): void
    {
        $result = itmap(fn(int $x): int => $x * 2)([5, 6]);
        self::assertEquals([10, 12], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function itmap_keys(): void
    {
        $result = itmap(fn(int $x, int $k): int => $x * 2 + $k)([5, 6]);
        self::assertEquals([10, 13], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function itmap_iterator(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = itmap(fn(int $x): int => $x * 2)($gen());
        self::assertEquals([10, 12], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function itmap_iterator_keys(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = itmap(fn(int $x, int $k): int => $x * 2 + $k)($gen());
        self::assertEquals([10, 13], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function amap(): void
    {
        $result = amap(fn(int $x): int => $x * 2)([5, 6]);
        self::assertEquals([10, 12], $result);
    }

    /**
     * @test
     */
    public function amap_keys(): void
    {
        $result = amap(fn(int $x, int $k): int => $x * 2 + $k)([5, 6]);
        self::assertEquals([10, 13], $result);
    }

    /**
     * @test
     */
    public function amap_iterator(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = amap(fn(int $x): int => $x * 2)($gen());
        self::assertEquals([10, 12], $result);
    }

    /**
     * @test
     */
    public function amap_iterator_keys(): void
    {
        $gen = function () {
            yield 5;
            yield 6;
        };
        $result = amap(fn(int $x, int $k): int => $x * 2 + $k)($gen());
        self::assertEquals([10, 13], $result);
    }

    /**
     * @test
     */
    public function itfilter(): void
    {
        $result = itfilter(fn(int $x): bool => !($x % 2))([5, 6, 7, 8]);
        self::assertEquals([1 => 6, 3 => 8], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function itfilter_default_callback(): void
    {
        $result = itfilter()([5, 0, '', 8]);
        self::assertEquals([0 => 5, 3 => 8], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function afilter(): void
    {
        $result = afilter(fn(int $x): bool => !($x % 2))([5, 6, 7, 8]);
        self::assertEquals([1 => 6, 3 => 8], $result);
    }

    /**
     * @test
     */
    public function afilter_iterator(): void
    {
        $gen = function () {
            yield from [5, 6, 7, 8];
        };
        $result = afilter(fn(int $x): bool => !($x % 2))($gen());
        self::assertEquals([1 => 6, 3 => 8], $result);
    }

    /**
     * @test
     */
    public function afilter_default_callback(): void
    {
        $result = afilter()([5, 0, '', 8]);
        self::assertEquals([0 => 5, 3 => 8], $result);
    }

    /**
     * @test
     */
    public function collect_array(): void
    {
        $result = collect()([1, 2, 3]);

        self::assertEquals([1, 2, 3], $result);
    }

    /**
     * @test
     */
    public function collect_iterator(): void
    {
        $it = static function () {
            yield 1;
            yield 2;
            yield 3;
        };

        $result = collect()($it());

        self::assertEquals([1, 2, 3], $result);
    }

    /**
     * @test
     */
    public function reduce(): void
    {
        $result = reduce(0, fn(int $collect, int $x) => $x + $collect)([1, 2, 3, 4, 5]);

        self::assertEquals(15, $result);
    }

    /**
     * @test
     */
    public function reduce_keys(): void
    {
        $result = reduceWithKeys(0, fn(int $collect, int $x, int $k) => $x + $collect + $k)([1, 2, 3, 4, 5]);

        self::assertEquals(25, $result);
    }

    /**
     * @test
     */
    public function reduce_iterable(): void
    {
        $gen = function() {
            yield from [1, 2, 3, 4, 5];
        };
        $result = reduce(0, fn(int $collect, int $x) => $x + $collect)($gen());

        self::assertEquals(15, $result);
    }

    /**
     * @test
     */
    public function reduce_iterable_keys(): void
    {
        $gen = function() {
            yield from [1, 2, 3, 4, 5];
        };
        $result = reduceWithKeys(0, fn(int $collect, int $x, int $k) => $x + $collect + $k)($gen());

        self::assertEquals(25, $result);
    }

    /**
     * @test
     */
    public function indexBy(): void
    {
        $in = [
            ['Jean-Luc', 'Picard'],
            ['James', 'Kirk'],
            ['Benjamin', 'Sisko'],
        ];

        $result = indexBy(fn(array $record) => $record[0])($in);

        self::assertEquals([
            'Jean-Luc' => ['Jean-Luc', 'Picard'],
            'James' => ['James', 'Kirk'],
            'Benjamin' => ['Benjamin', 'Sisko'],
        ], $result);
    }

    /**
     * @test
     */
    public function keyedMap(): void
    {
        $result = keyedMap(static fn($k, $v) => $k + $v)([1 => 1, 2=> 2, 3 => 3]);
        self::assertEquals([0 => 2, 1 => 4, 2 => 6], $result);
    }

    /**
     * @test
     */
    public function keyedMapWithKeyCallback(): void
    {
        $values = static fn($k, $v) => $k * $v;
        $keys = static fn($k, $v) => $k + $v;

        $result = keyedMap($values, $keys)([1 => 1, 2=> 2, 3 => 3]);
        self::assertEquals([2 => 1, 4 => 4, 6 => 9], $result);
    }

    /**
     * @test
     */
    public function first(): void
    {
        $list = [1, 2, 3, 4, 5];

        $result = first(fn(int $x): bool => ! ($x % 2))($list);

        self::assertEquals(2, $result);
    }

    /**
     * @test
     */
    public function firstValue(): void
    {
        $list = [
            new class {
                public function foo() { return null; }
            },
            new class {
                public function foo() { return 0; }
            },
            new class {
                public function foo() { return 2; }
            },
            new class {
                public function foo() { return 3; }
            },
        ];

        $result = firstValue(static fn(object $object): ?int => $object->foo())($list);

        self::assertEquals(2, $result);
    }

    /**
     * @test
     */
    public function firstWithNoMatch(): void
    {
        $list = [1, 3, 5, 7, 9];

        $result = first(fn(int $x): bool => ! ($x % 2))($list);

        self::assertNull($result);
    }

    /**
     * @test
     */
    public function anyMatch(): void
    {
        $list = [1, 2, 3, 5, 7, 9];

        $result = any(fn(int $x): bool => ! ($x % 2))($list);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function anyNoMatch(): void
    {
        $list = [1, 3, 5, 7, 9];

        $result = any(fn(int $x): bool => ! ($x % 2))($list);

        self::assertFalse($result);
    }


    /**
     * @test
     */
    public function allMatch(): void
    {
        $list = [2, 4, 6];

        $result = all(fn(int $x): bool => ! ($x % 2))($list);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function allNoMatch(): void
    {
        $list = [2, 3, 4];

        $result = all(fn(int $x): bool => ! ($x % 2))($list);

        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function flatten(): void
    {
        $a = [1, 2, [3, 4], [5, [6, 7]]];
        $result = flatten($a);

        self::assertEquals([1, 2, 3, 4, 5, 6, 7], $result);
    }
}
