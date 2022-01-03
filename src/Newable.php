<?php

declare(strict_types=1);

namespace Crell\fp;

/**
 * Pipe-friendly alternative to a constructor.
 */
trait Newable
{
    /**
     * @param mixed ...$args
     */
    public static function new(...$args): static
    {
        // Because this is completely variadic, phpstan's normal
        // whining about static constructors is not applicable.
        // @phpstan-ignore-next-line
        return new static(...$args);
    }
}
