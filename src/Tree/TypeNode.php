<?php

namespace perf\TypeValidation\Tree;

interface TypeNode
{
    /**
     * Tells whether provided value is valid according to current type node.
     *
     * @param mixed $value Value to validate.
     *
     * @return bool
     */
    public function isValid($value): bool;

    /**
     * Returns a textual representation of the current type node.
     *
     * @return string
     */
    public function __toString();
}
