<?php

declare(strict_types=1);

namespace Crell\fp;

function compose(callable ...$fns): callable
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
