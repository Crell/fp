<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\TestCase;

use function Crell\fp\itmap;

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


}
