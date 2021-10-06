<?php

declare(strict_types=1);

namespace Crell\AttributeUtils\Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use function Crell\fp\pipe_compose;
use function Crell\fp\pipe_iterate;
use function Crell\fp\pipe_reduce;

/**
 * @Revs(10)
 * @Iterations(3)
 * @Warmup(2)
 * @BeforeMethods({"setUp"})
 * @AfterMethods({"tearDown"})
 * @OutputTimeUnit("milliseconds", precision=3)
 */
class FpBenchmarks
{
    protected array $funcs;

    public function setUp(): void
    {
        $func = static fn($x) => $x;
        $this->funcs = array_fill(0, 10, $func);
    }

    public function tearDown(): void {}

    public function benchPipeCompose(): void
    {
        pipe_compose(5, ...$this->funcs);
    }

    public function benchPipeIterate(): void
    {
        pipe_iterate(5, ...$this->funcs);
    }

    public function benchPipeReduce(): void
    {
        pipe_reduce(5, ...$this->funcs);
    }

}
