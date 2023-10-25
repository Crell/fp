<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NewableTest extends TestCase
{
    #[Test]
    public function no_constructor(): void
    {
        $c = NoConstructor::new();

        self::assertInstanceOf(NoConstructor::class, $c);
    }

    #[Test]
    public function constructor_with_positional_args(): void
    {
        $c = HasConstructor::new(1, 'hello');

        self::assertInstanceOf(HasConstructor::class, $c);
        self::assertEquals(1, $c->x);
        self::assertEquals('hello', $c->name);
    }

    #[Test]
    public function constructor_with_named_args(): void
    {
        $c = HasConstructor::new(name: 'hello', x: 1);

        self::assertInstanceOf(HasConstructor::class, $c);
        self::assertEquals(1, $c->x);
        self::assertEquals('hello', $c->name);
    }

    #[Test]
    public function constructor_with_variadic_args(): void
    {
        $c = HasConstructor::new(...[1, 'hello']);

        self::assertInstanceOf(HasConstructor::class, $c);
        self::assertEquals(1, $c->x);
        self::assertEquals('hello', $c->name);
    }

    #[Test]
    public function constructor_with_variadic_named_args(): void
    {
        $c = HasConstructor::new(...['name' => 'hello', 'x' => 1]);

        self::assertInstanceOf(HasConstructor::class, $c);
        self::assertEquals(1, $c->x);
        self::assertEquals('hello', $c->name);
    }
}


class NoConstructor
{
    use Newable;
}

class HasConstructor
{
    use Newable;

    public function __construct(public int $x, public string $name) {}
}
