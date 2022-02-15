<?php

declare(strict_types=1);

namespace Crell\fp;

function compose(callable ...$fns): \Closure
{
    return static function (mixed $arg) use ($fns): mixed  {
        foreach ($fns as $fn) {
            $arg = $fn($arg);
        }
        return $arg;
    };
}

function pipe(mixed $arg, callable ...$fns): mixed
{
    foreach ($fns as $fn) {
        $arg = $fn($arg);
    }
    return $arg;
}

function trace(mixed $arg): mixed
{
    var_dump($arg);
    return $arg;
}

/**
 * Wrap a unary function in null-pass-through logic.
 *
 * Especially useful to wrap calls inside a pipe() chain.
 *
 * @param callable $c
 * @return callable
 */
function maybe(callable $c): callable
{
    return static fn (mixed $val): mixed => is_null($val) ? null : $c($val);
}
