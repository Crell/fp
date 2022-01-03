<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\TestCase;

class ObjectTest extends TestCase
{
    /**
     * @test
     */
    public function prop_read(): void
    {
        $o = new class {
            public int $x = 1;
            public string $name = 'hello';
        };

        $result = prop('x')($o);

        self::assertEquals(1, $result);
    }

    /**
     * @test
     */
    public function prop_read_missing(): void
    {
        $this->expectWarning();

        $o = new class {
            public int $x = 1;
            public string $name = 'hello';
        };

        prop('missing')($o);
    }

    /**
     * @test
     */
    public function method_no_args(): void
    {
        $o = new class {
            public function do(): int { return 1; }
        };

        $result = method('do')($o);

        self::assertEquals(1, $result);
    }

    /**
     * @test
     */
    public function method_with_args(): void
    {
        $o = new class {
            public function do(int $x): int { return $x; }
        };

        $result = method('do', 1)($o);

        self::assertEquals(1, $result);
    }
}
