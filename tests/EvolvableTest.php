<?php

declare(strict_types=1);

namespace Crell\fp;

use PHPUnit\Framework\TestCase;

class EvolvableTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_props_evolvablee(): void
    {
        $c = new Constructor(1, 2, 3);

        $c2 = $c->with(public: 4, protected: 5, private: 6);

        $expected = new Constructor(4, 5, 6);

        self::assertEquals($expected, $c2);
    }

    /**
     * @test
     */
    public function defined_props_evolvable(): void
    {
        $c = new Props(1, 2, 3);

        $c2 = $c->with(public: 4, protected: 5, private: 6);

        $expected = new Props(4, 5, 6);

        self::assertEquals($expected, $c2);
    }

    /**
     * @test
     */
    public function undefined_props_evolvable(): void
    {
        $c = new Uninitialized();

        $c2 = $c->with(set: 6, notSet: 1);

        self::assertEquals(6, $c2->set);
        self::assertEquals(1, $c2->notSet);
    }
}

class Constructor
{
    use Evolvable;

    public function __construct(
        public int $public,
        protected int $protected,
        private int $private,
    ) {}
}

class Props
{
    use Evolvable;

    public int $public;
    protected int $protected;
    private int $private;

    public function __construct(int $public, int $protected, int $private)
    {
        $this->public = $public;
        $this->protected = $protected;
        $this->private = $private;
    }
}

class Uninitialized
{
    use Evolvable;

    public int $notSet;

    public int $set = 5;
}
