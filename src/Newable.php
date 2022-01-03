<?php

declare(strict_types=1);

namespace Crell\fp;

/**
 * Pipe-friendly alternative to a constructor.
 */
trait Newable
{
    public static function new(...$args): static
    {
        return new static(...$args);
    }
}
