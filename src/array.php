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
