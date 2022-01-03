<?php

declare(strict_types=1);

namespace Crell\fp;

/**
 * Returns a callable that will read a property off an object.
 *
 * This is mostly to make property reading pipeable.
 *
 * Note: This only works for public properties.
 */
function prop(string $prop): callable
{
    return static fn (object $o): mixed => $o->$prop;
}

/**
 * Returns a callable that will invoke a method off an object.
 *
 * This is mostly to make method invocation pipeable.
 *
 * Note: This only works for public methods.
 *
 * @param array<int|string, mixed> ...$args
 */
function method(string $method, ...$args): callable
{
    return static fn (object $o): mixed => $o->$method(...$args);
}
