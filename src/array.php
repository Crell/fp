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

function afilter(callable $c): callable
{
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

function itfilter(callable $c): callable
{
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
