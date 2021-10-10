<?php

declare(strict_types=1);

namespace Crell\fp;

use function is_array;
use function array_map;
use function array_filter;
use function array_reduce;

function amap(callable $c): callable
{
    return static function (iterable $it) use ($c): array {
        if (is_array($it)) {
            return array_map($c, $it);
        }
        $result = [];
        foreach ($it as $k => $v) {
            $result[$k] = $c($v);
        }
        return $result;
    };
}

function itmap(callable $c): callable
{
    return static function (iterable $it) use ($c): iterable {
        foreach ($it as $k => $v) {
            yield $k =>$c($v);
        }
    };
}

function afilter(?callable $c = null): callable
{
    $c ??= static fn (mixed $v, mixed $k = null): bool => (bool)$v;
    return static function (iterable $it) use ($c): array {
        if (is_array($it)) {
            return array_filter($it, $c);
        }
        $result = [];
        foreach ($it as $k => $v) {
            if ($c($v, $k)) {
                $result[$k] = $v;
            }
        }
        return $result;
    };
}

function itfilter(?callable $c = null): callable
{
    $c ??= static fn (mixed $v, mixed $l): bool => (bool)$v;
    return static function (iterable $it) use ($c): iterable {
        foreach ($it as $k => $v) {
            if ($c($v, $k)) {
                yield $k => $v;
            }
        }
    };
}

/**
 * @todo for PHP 8.1, this can change to leverage FCC instead of returning a function.
 */
function collect(): callable
{
    return static fn(iterable $a): array
        => is_array($a) ? $a : iterator_to_array($a);
}

function reduce(mixed $init, callable $c): callable
{
    return static function (iterable $it) use ($init, $c): mixed {
        if (is_array($it)) {
            return array_reduce($it, $c, $init);
        }
        foreach ($it as $k => $v) {
            $init = $c($init, $v);
        }
        return $init;
    };
}

function indexBy(callable $keyMaker): callable
{
    return static function (array $arr) use ($keyMaker): array {
        $ret = [];
        foreach ($arr as $v) {
            $ret[$keyMaker($v)] = $v;
        }
        return $ret;
    };
}

/**
 * @todo It might make more sense to use our map instead of the native one. Not sure.
 */
function keyedMap(callable $values, ?callable $keys = null): callable
{
    $keys ??= function (): int {
        static $counter = 0;
        return $counter++;
    };
    return static fn(array $a): array => array_combine(
        array_map($keys, array_keys($a), array_values($a)),
        array_map($values, array_keys($a), array_values($a))
    );
}

/**
 * @return mixed
 *   The first value that matches the provided filter, or null if none was found.
 */
function first(callable $c): callable
{
    return static function (iterable $it) use ($c): mixed {
        foreach ($it as $k => $v) {
            if ($c($v, $k)) {
                return $v;
            }
        }
        return null;
    };
}

/**
 * Invokes the callable on each item in the iterable, and returns the first truthy result.
 *
 * @return mixed
 */
function firstValue(callable $c): callable
{
    return static function (iterable $it) use ($c): mixed {
        foreach ($it as $k => $v) {
            if ($res = $c($v, $k)) {
                return $res;
            }
        }
        return null;
    };
}

function any(callable $c): callable
{
    return static function (iterable $it) use ($c): bool {
        foreach ($it as $k => $v) {
            if ($c($v, $k)) {
                return true;
            }
        }
        return false;
    };
}

function all(callable $c): callable
{
    return static function (iterable $it) use ($c): bool {
        foreach ($it as $k => $v) {
            if (! $c($v, $k)) {
                return false;
            }
        }
        return true;
    };
}

function flatten(array $arr): array
{
    $flat = [];
    array_walk_recursive($arr, static function ($v) use (&$flat) {
        $flat[] = $v;
    });

    return $flat;
}
