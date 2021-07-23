<?php

declare(strict_types=1);

namespace Crell\fp;

function amap(callable $c): callable
{
    return static function (iterable $it) use ($c) {
        $result = [];
        foreach ($it as $k => $v) {
            $result[$k] = $c($v);
        }
        return $result;
    };
}
function itmap(callable $c): callable
{
    return static function (iterable $it) use ($c) {
        foreach ($it as $k => $v) {
            yield $k =>$c($v);
        }
    };
}

function afilter(?callable $c = null): callable
{
    $c ??= static fn (mixed $v, mixed $l): bool => (bool)$v;
    return static function (iterable $it) use ($c) {
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
    return static function (iterable $it) use ($c) {
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
    return static fn(iterable $a)
        => is_array($a) ? $a : iterator_to_array($a);
}

function reduce(mixed $init, callable $c): callable
{
    return static function (iterable $it) use ($init, $c) {
        foreach ($it as $k => $v) {
            $init = $c($init, $v);
        }
        return $init;
    };
}

function indexBy(callable $keyMaker): callable
{
    return static function (array $arr) use ($keyMaker) {
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
function keyedMap(callable $values, ?callable $keys = null)
{
    $keys ??= function () {
        static $counter = 0;
        return $counter++;
    };
    return static fn(array $a) => array_combine(
        array_map($keys, array_keys($a), array_values($a)),
        array_map($values, array_keys($a), array_values($a))
    );
}
