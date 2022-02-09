<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
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
                public function foo(): mixed { return null; }
            },
            new class {
                public function foo(): int { return 0; }
            },
            new class {
                public function foo(): int { return 2; }
            },
            new class {
                public function foo(): int { return 3; }
            },
        ];

        // PHPStan is not smart enough to know how to deal with the anon class. This is valid.
        // @phpstan-ignore-next-line
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
    public function firstWithKeys(): void
    {
        $list = [1, 2, 3, 4, 5];

        $result = firstWithKeys(static fn (int $x, int $k): bool => !($x % 2) && $k % 2)($list);

        self::assertEquals(2, $result);
    }

    /**
     * @test
     */
    public function firstValueWithKeys(): void
    {
        $list = [
            new class {
                public function foo(): mixed { return 0; }
            },
            new class {
                public function foo(): int { return -1; }
            },
            // This is the one that should be returned.
            new class {
                public function foo(): int { return 2; }
            },
            new class {
                public function foo(): int { return 3; }
            },
        ];

        // PHPStan is not smart enough to know how to deal with the anon class. This is valid.
        // @phpstan-ignore-next-line
        $result = firstValueWithKeys(static fn(object $object, $key): ?int => $key + $object->foo())($list);

        self::assertEquals(4, $result);
    }

    /**
     * @test
     */
    public function firstWithKeysWithNoMatch(): void
    {
        $list = [1, 3, 5, 7, 9];

        $result = firstWithKeys(fn(int $x, int $k): bool => ! ($x % 2))($list);

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
    public function anyWithKeysMatch(): void
    {
        $list = [1, 2, 3, 5, 7, 9];

        $result = anyWithKeys(fn(int $x, int $k): bool => ! ($x % 2))($list);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function anyWithKeysNoMatch(): void
    {
        $list = [1, 3, 5, 7, 9];

        $result = anyWithKeys(fn(int $x, int $k): bool => ! ($x % 2))($list);

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
    public function allWithKeysMatch(): void
    {
        $list = [2, 4, 6];

        $result = allWithKeys(fn(int $x, int $k): bool => ! ($x % 2))($list);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function allWithKeysNoMatch(): void
    {
        $list = [2, 3, 4];

        $result = allWithKeys(fn(int $x, int $k): bool => ! ($x % 2))($list);

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

    /**
     * @test
     */
    public function append(): void
    {
        $a = [1, 2, 3, 4];
        $result = append(5)($a);

        self::assertEquals([1, 2, 3, 4, 5], $result);
    }

    /**
     * @test
     */
    public function append_keys(): void
    {
        $a = ['a' => 'A', 'b' => 'B'];
        $result = append('C', 'c')($a);

        self::assertEquals(['a' => 'A', 'b' => 'B', 'c' => 'C'], $result);
    }

    /**
     * @test
     */
    public function iterate(): void
    {
        $a = 0;

        $mapper = static fn (int $x): int => $x + 1;

        // This will produce an infinite list.

        $iterable = iterate($a, $mapper);

        $result = [];
        for ($i = 0; $i < 10; ++$i) {
            $result[] = $iterable->current();
            $iterable->next();
        }

        self::assertEquals(range(0, 9), $result);
    }

    /**
     * @test
     */
    public function atake(): void
    {
        $a = array_combine(range('a', 'z'), range('A', 'Z'));

        $result = atake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B', 'c' => 'C'], $result);
    }

    /**
     * @test
     */
    public function atake_iterator(): void
    {
        $l = array_combine(range('a', 'z'), range('A', 'Z'));
        $a = new \ArrayIterator($l);

        $result = atake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B', 'c' => 'C'], $result);
    }

    /**
     * @test
     */
    public function atake_insufficient(): void
    {
        $a = array_combine(range('a', 'b'), range('A', 'B'));

        $result = atake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B'], $result);
    }

    /**
     * @test
     */
    public function atake_iterator_insufficient(): void
    {
        $l = array_combine(range('a', 'b'), range('A', 'B'));
        $a = new \ArrayIterator($l);

        $result = atake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B'], $result);
    }

    /**
     * @test
     */
    public function ittake(): void
    {
        $a = array_combine(range('a', 'z'), range('A', 'Z'));

        $result = ittake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B', 'c' => 'C'], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function ittake_iterator(): void
    {
        $l = array_combine(range('a', 'z'), range('A', 'Z'));
        $a = new \ArrayIterator($l);

        $result = ittake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B', 'c' => 'C'], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function ittake_insufficient(): void
    {
        $a = array_combine(range('a', 'b'), range('A', 'B'));

        $result = ittake(3)($a);

        self::assertEquals(['a' => 'A', 'b' => 'B'], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function nth(): void
    {
        $a = 0;

        $mapper = static fn (int $x): int => $x + 1;

        $result = nth(2, $a, $mapper);

        self::assertEquals(1, $result);
    }

    /**
     * @test
     */
    public function head(): void
    {
        $a = [1, 2, 3];

        self::assertEquals(1, head($a));
    }

    /**
     * @test
     */
    public function head_empty(): void
    {
        $a = [];

        self::assertNull(head($a));
    }

    /**
     * @test
     */
    public function tail(): void
    {
        $a = [1, 2, 3];

        self::assertEquals([2, 3], tail($a));
    }

    /**
     * @test
     */
    public function tail_empty(): void
    {
        $a = [];

        self::assertEquals([], tail($a));
    }

    /**
     * @test
     */
    public function headtail(): void
    {
        $a = [1, 2, 3];

        $first = static fn (int $count, int $val) => $count - $val;
        $rest = static fn (int $count, int $val) => $count + $val;

        $result = headtail(5, $first, $rest)($a);

        self::assertEquals(9, $result);
    }

    /**
     * @test
     */
    public function headtail_empty(): void
    {
        $a = [];

        $first = static fn (int $count, int $val) => $count - $val;
        $rest = static fn (int $count, int $val) => $count + $val;

        $result = headtail(5, $first, $rest)($a);

        self::assertEquals(5, $result);
    }

    /**
     * @test
     */
    public function headtail_iterable(): void
    {
        $a = (function() {
            yield from [1, 2, 3];
        })();

        $first = static fn (int $count, int $val) => $count - $val;
        $rest = static fn (int $count, int $val) => $count + $val;

        $result = headtail(5, $first, $rest)($a);

        self::assertEquals(9, $result);
    }

    /**
     * @test
     */
    public function headtail_empty_iterable(): void
    {
        $a = new \ArrayIterator([]);

        $first = static fn (int $count, int $val) => $count - $val;
        $rest = static fn (int $count, int $val) => $count + $val;

        $result = headtail(5, $first, $rest)($a);

        self::assertEquals(5, $result);
    }
}
