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
    public function amap(): void
    {
        $result = amap(fn(int $x): int => $x * 2)([5, 6]);
        self::assertEquals([10, 12], $result);
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
    public function afilter(): void
    {
        $result = afilter(fn(int $x): bool => !($x % 2))([5, 6, 7, 8]);
        self::assertEquals([1 => 6, 3 => 8], $result);
    }


}
