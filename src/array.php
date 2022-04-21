<?php

declare(strict_types=1);

namespace Crell\fp;

use function is_array;
use function array_map;
use function array_filter;
use function array_reduce;

function amap(callable $c): \Closure
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

/**
 * Like amap(), but also pass the key of each entry.
 *
 * This has to be a separate opt-in function because internal
 * PHP functions no longer allow passing extra arguments, while
 * user-defined functions do.  That means a combined function
 * would be incompatible with single-argument internal functions.
 */
function amapWithKeys(callable $c): \Closure
{
    return static function (iterable $it) use ($c): array {
        if (is_array($it)) {
            // Ensure that the keys are preserved in the result.
            $keys = array_keys($it);
            return array_combine($keys, array_map($c, $it, $keys));
        }
        $result = [];
        foreach ($it as $k => $v) {
            $result[$k] = $c($v, $k);
        }
        return $result;
    };
}

function itmap(callable $c): \Closure
{
    return static function (iterable $it) use ($c): iterable {
        foreach ($it as $k => $v) {
            yield $k =>$c($v);
        }
    };
}

/**
 * Like itmap(), but also pass the key of each entry.
 *
 * This has to be a separate opt-in function because internal
 * PHP functions no longer allow passing extra arguments, while
 * user-defined functions do.  That means a combined function
 * would be incompatible with single-argument internal functions.
 */
function itmapWithKeys(callable $c): \Closure
{
    return static function (iterable $it) use ($c): iterable {
        foreach ($it as $k => $v) {
            yield $k =>$c($v, $k);
        }
    };
}

function afilter(?callable $c = null): \Closure
{
    $c ??= static fn (mixed $v, mixed $k = null): bool => (bool)$v;
    return static function (iterable $it) use ($c): array {
        if (is_array($it)) {
            return array_filter($it, $c);
        }
        $result = [];
        foreach ($it as $k => $v) {
            if ($c($v)) {
                $result[$k] = $v;
            }
        }
        return $result;
    };
}

function itfilter(?callable $c = null): \Closure
{
    $c ??= static fn (mixed $v): bool => (bool)$v;
    return static function (iterable $it) use ($c): iterable {
        foreach ($it as $k => $v) {
            if ($c($v)) {
                yield $k => $v;
            }
        }
    };
}

/**
 * Like afilter(), but also pass the key of each entry.
 *
 * This has to be a separate opt-in function because internal
 * PHP functions no longer allow passing extra arguments, while
 * user-defined functions do.  That means a combined function
 * would be incompatible with single-argument internal functions.
 */
function afilterWithKeys(?callable $c = null): \Closure
{
    $c ??= static fn (mixed $v, mixed $k = null): bool => (bool)$v;
    return static function (iterable $it) use ($c): array {
        if (is_array($it)) {
            return array_filter($it, $c, ARRAY_FILTER_USE_BOTH);
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

/**
 * Like itfilter(), but also pass the key of each entry.
 *
 * This has to be a separate opt-in function because internal
 * PHP functions no longer allow passing extra arguments, while
 * user-defined functions do.  That means a combined function
 * would be incompatible with single-argument internal functions.
 */
function itfilterWithKeys(?callable $c = null): \Closure
{
    $c ??= static fn (mixed $v, mixed $k): bool => (bool)$v;
    return static function (iterable $it) use ($c): iterable {
        foreach ($it as $k => $v) {
            if ($c($v, $k)) {
                yield $k => $v;
            }
        }
    };
}

/**
 * This is most often used as collect(...) in a pipe.
 */
function collect(iterable $a): array
{
    return is_array($a) ? $a : iterator_to_array($a);
}

function reduce(mixed $init, callable $c): \Closure
{
    return static function (iterable $it) use ($init, $c): mixed {
        if (is_array($it)) {
            return array_reduce($it, $c, $init);
        }
        foreach ($it as $v) {
            $init = $c($init, $v);
        }
        return $init;
    };
}

/**
 * Reduce a list, but stop early if $stop($runningValue) returns true.
 */
function reduceUntil(mixed $init, callable $c, callable $stop): \Closure
{
    return static function (iterable $it) use ($init, $c, $stop): mixed {
        foreach ($it as $v) {
            $init = $c($init, $v);
            if ($stop($init)) {
                return $init;
            }
        }
        return $init;
    };
}

/**
 * Same as reduce, but the key of each entry is also passed to the callback.
 *
 * This is a separate function from reduce() because there's no good way to
 * emulate this behavior with the built-in array_reduce(), and no good way to
 * tell if the callback wants a key.  We could use a single function for all of
 * it, but then we couldn't fall back to array_reduce() in the common case, which
 * is faster than doing our own iteration.
 */
function reduceWithKeys(mixed $init, callable $c): \Closure
{
    return static function (iterable $it) use ($init, $c): mixed {
        foreach ($it as $k => $v) {
            $init = $c($init, $v, $k);
        }
        return $init;
    };
}

function indexBy(callable $keyMaker): \Closure
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
 * Maps an incoming array using a separate mapper for the value and key.
 */
function keyedMap(callable $values, ?callable $keys = null): \Closure
{
    $keys ??= static function (): int {
        static $counter = 0;
        return $counter++;
    };
    return static fn(array $a): array => array_combine(
        array_map($keys, array_keys($a), array_values($a)),
        array_map($values, array_keys($a), array_values($a))
    );
}

/**
 * Returns the first value that matches the provided filter, or null if none was found.
 */
function first(callable $c): \Closure
{
    return static function (iterable $it) use ($c): mixed {
        foreach ($it as $v) {
            if ($c($v)) {
                return $v;
            }
        }
        return null;
    };
}

/**
 * Returns the first value that matches the provided filter, or null if none was found.
 */
function firstWithKeys(callable $c): \Closure
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
 */
function firstValue(callable $c): \Closure
{
    return static function (iterable $it) use ($c): mixed {
        foreach ($it as $v) {
            if ($res = $c($v)) {
                return $res;
            }
        }
        return null;
    };
}

/**
 * Invokes the callable on each item in the iterable, and returns the first truthy result.
 */
function firstValueWithKeys(callable $c): \Closure
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

function any(callable $c): \Closure
{
    return static function (iterable $it) use ($c): bool {
        foreach ($it as $v) {
            if ($c($v)) {
                return true;
            }
        }
        return false;
    };
}

function anyWithKeys(callable $c): \Closure
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

function all(callable $c): \Closure
{
    return static function (iterable $it) use ($c): bool {
        foreach ($it as $v) {
            if (! $c($v)) {
                return false;
            }
        }
        return true;
    };
}

function allWithKeys(callable $c): \Closure
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

/**
 * @param array<mixed> $arr
 * @return array<mixed>
 */
function flatten(array $arr): array
{
    $flat = [];
    array_walk_recursive($arr, static function ($v) use (&$flat) {
        $flat[] = $v;
    });

    return $flat;
}

function append(mixed $value, mixed $key = null): \Closure
{
    return static function (array $it) use ($value, $key): array {
        if ($key) {
            $it[$key] = $value;
        } else {
            $it[] = $value;
        }
        return $it;
    };
}

/**
 * Produces an infinite list from applying the same operation repeatedly to an input.
 *
 * The initial value is yielded first. Each subsequent call will yield applying
 * the function to the result of the previous iteration.
 *
 * Note: This generator produces an infinite list!  Make sure you have some termination
 * check when calling it to avoid iterating forever.
 *
 * @param mixed $init
 *   The initial value.
 * @param callable $mapper
 *   A function that will turn one element in a sequence into the next.
 * @return \Generator<mixed>
 */
function iterate(mixed $init, callable $mapper): \Generator
{
    yield $init;
    while (true) {
        yield $init = $mapper($init);
    }
}

/**
 * Returns a limited set of values from an iterable as an array.
 *
 * This is roughly equivalent to an SQL LIMIT clause, but for iterables.
 */
function atake(int $count): \Closure
{
    return static function (iterable $a) use ($count): array {
        if (is_array($a)) {
            return array_slice($a, 0, $count);
        }
        $ret = [];
        foreach ($a as $k => $v) {
            if (--$count < 0) {
                break;
            }
            $ret[$k] = $v;
        }
        return $ret;
    };
}

/**
 * Yields a limited set of values from an iterable, lazily.
 *
 * This is roughly equivalent to an SQL LIMIT clause, but for iterables.
 */
function ittake(int $count): \Closure
{
    return static function (array|\Iterator $a) use ($count): iterable {
        // No idea if this is faster than manually foreach()ing, but it's slicker.
        yield from is_array($a)
            ? array_slice($a, 0, $count)
            : new \LimitIterator($a, 0, $count);
    };
}

/**
 * Returns the n-nth item from a generated sequence.
 *
 * The $init value is considered the first value. That is, passing $count = 1
 * will return $init unmodified.
 *
 * This could also be implemented based on iterate(), or with a reduce().
 * Inlining the while() loop is most performant, however.
 */
function nth(int $count, mixed $init, callable $mapper): mixed
{
    while(--$count > 0) {
        $init = $mapper($init);
    }
    return $init;
}

/**
 * @param array<mixed> $a
 */
function head(array $a): mixed
{
    return $a[0] ?? null;
}

/**
 * @param array<mixed> $a
 * @return array<mixed>
 */
function tail(array $a): array
{
    return array_slice($a, 1);
}

/**
 * Reduces a list, using a different reducer for the first element and the rest.
 *
 * Primarily useful for fencepost type situations.
 *
 * @param mixed $init
 *   The initial value to reduce.
 * @param callable $first
 *   The callable to apply to the first item only.
 * @param callable $rest
 *   The callable to apply to all items other than the first.
 * @return callable
 */
function headtail(mixed $init, callable $first, callable $rest): \Closure
{
    // \IteratorAggregate is impossible in practice, so `iterable` is too wide.
    return static function (array|\Iterator $it) use ($init, $first, $rest): mixed {
        $head = is_array($it) ? current($it) : $it->current();
        if (!$head) {
            return $init;
        }

        $init = $first($init, $head);

        if (is_array($it)) {
            return reduce($init, $rest)(tail($it));
        }

        // Because the iterator has already been started, we cannot use the
        // foreach() loop in reduce().  Instead we have to do it the manual
        // way here.  Blech.
        $it->next();
        while($it->valid()) {
            $init = $rest($init, $it->current());
            $it->next();
        }
        return $init;
    };
}
