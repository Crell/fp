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

/**
 * Returns a callable that determines if a value is of a specified type.
 *
 * @param string $type
 *   The type to check. If the type is an object, it will be an instanceof check.
 *   Otherwise the appropriate is_*() function will be called.
 */
function typeIs(string $type): callable
{
    return static fn (mixed $v) => match (true) {
        $type === 'int' => \is_int($v),
        $type === 'string' => \is_string($v),
        $type === 'float' => \is_float($v),
        $type === 'bool' => \is_bool($v),
        $type === 'array' => \is_array($v),
        $type === 'resource' => \is_resource($v),
        class_exists($type), interface_exists($type) => $v instanceof $type,
    };
}
