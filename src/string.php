<?php

declare(strict_types=1);

namespace Crell\fp;

function replace(array|string $find, array|string $replace): callable
{
    return static fn (string $s): string => str_replace($find, $replace, $s);
}

function implode(string $glue): callable
{
    return static fn (array $a): string => \implode($glue, $a);
}

/**
 * @param non-empty-string $delimiter
 * @return callable
 */
function explode(string $delimiter): callable
{
    return static fn (string $s): array => \explode($delimiter, $s);
}
