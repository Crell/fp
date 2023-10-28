<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ObjectTest extends TestCase
{
    #[Test]
    public function prop_read(): void
    {
        $o = new class {
            public int $x = 1;
            public string $name = 'hello';
        };

        $result = prop('x')($o);

        self::assertEquals(1, $result);
    }

    #[Test]
    public function prop_read_missing(): void
    {
        set_error_handler(
            static function ( $errno, $errstr ) {
                restore_error_handler();
                throw new \Exception( $errstr, $errno );
            },
            E_WARNING
        );

        $this->expectException(\Exception::class);

        $o = new class {
            public int $x = 1;
            public string $name = 'hello';
        };

        prop('missing')($o);
    }

    #[Test]
    public function method_no_args(): void
    {
        $o = new class {
            public function do(): int { return 1; }
        };

        $result = method('do')($o);

        self::assertEquals(1, $result);
    }

    #[Test]
    public function method_with_args(): void
    {
        $o = new class {
            public function do(int $x): int { return $x; }
        };

        $result = method('do', 1)($o);

        self::assertEquals(1, $result);
    }

    /**
     * @param string $type
     * @param mixed $val
     * @param bool $match
     */
    #[Test, DataProvider('typeIsExamples')]
    public function typeIs(string $type, mixed $val, bool $match): void
    {
        self::assertEquals($match, typeIs($type)($val));
    }

    /**
     * @return iterable<array{string, mixed, bool}>
     */
    public static function typeIsExamples(): iterable
    {
        // Things that are.
        yield ['int', 1, true];
        yield ['string', '1', true];
        yield ['float', 1.0, true];
        yield ['bool', true, true];
        yield [\SplStack::class, new \SplStack(), true];

        // Things that are not.
        yield ['int', '1', false];
        yield ['string', 1, false];
        yield ['float', 1, false];
        yield ['float', [], false];
        yield ['bool', 1, false];
        yield [\SplStack::class, new \SplPriorityQueue(), false];
    }
}
