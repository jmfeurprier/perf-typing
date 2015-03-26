<?php

namespace perf\Typing;

/**
 *
 *
 */
class TypeValidator
{

    /**
     *
     *
     * @param string $typeDefinition
     * @param mixed $value
     * @return bool
     */
    public function isValid($typeDefinition, $value)
    {
        if ('mixed' === $typeDefinition) {
            return true;
        }

        if ('int' === $typeDefinition) {
            return is_int($value);
        }

        if ('integer' === $typeDefinition) {
            return is_int($value);
        }

        if ('float' === $typeDefinition) {
            return is_float($value);
        }

        if ('double' === $typeDefinition) {
            return is_float($value);
        }

        if ('numeric' === $typeDefinition) {
            return is_numeric($value);
        }

        if ('string' === $typeDefinition) {
            return is_string($value);
        }

        if ('bool' === $typeDefinition) {
            return is_bool($value);
        }

        if ('boolean' === $typeDefinition) {
            return is_bool($value);
        }

        if ('scalar' === $typeDefinition) {
            return is_scalar($value);
        }

        if ('array' === $typeDefinition) {
            return is_array($value);
        }

        if ('object' === $typeDefinition) {
            return is_object($value);
        }

        if ('resource' === $typeDefinition) {
            return is_resource($value);
        }

        return false;
    }
}
