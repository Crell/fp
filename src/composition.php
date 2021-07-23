<?php

declare(strict_types=1);

namespace Crell\fp;

function compose(callable|iterable ...$fns): callable
{
    return static function (mixed $arg) use ($fns): mixed  {
        foreach ($fns as $fn) {
            $arg = is_iterable($fn) ? compose(...$fn)($arg) : $fn($arg);
        }
        return $arg;
    };
}

function pipe(mixed $arg, callable|iterable ...$fns): mixed
{
    return compose(...$fns)($arg);
}
