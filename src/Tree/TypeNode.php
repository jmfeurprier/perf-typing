<?php

namespace Jmf\TypeValidation\Tree;

use Stringable;

interface TypeNode extends Stringable
{
    /**
     * Tells whether provided value is valid according to current type node.
     */
    public function isValid(mixed $value): bool;
}
