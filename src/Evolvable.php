<?php

declare(strict_types=1);

namespace Crell\fp;

/**
 * Based on a very similar class from brendt.
 * @see https://github.com/spatie/php-cloneable
 */
trait Evolvable
{
    public function with(...$values): static
    {
        $r = new \ReflectionClass(static::class);

        $clone = $r->newInstanceWithoutConstructor();

        // If a property is still undefined, it won't show up from just iterating $this.
        // We have to go through reflection to get the complete list of properties.
        foreach ($r->getProperties() as $rProp) {
            $field = $rProp->name;
            if (array_key_exists($field, $values)) {
                $clone->$field = $values[$field];
            } elseif ($rProp->isInitialized($this)) {
                $clone->$field = $rProp->getValue($this);
            }
            // If the field is uninitialized, leave it as is.
        }

        return $clone;
    }
}
