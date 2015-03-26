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
        static $map = array(
            'int'      => 'is_int',
            'integer'  => 'is_int',
            'float'    => 'is_float',
            'double'   => 'is_float',
            'numeric'  => 'is_numeric',
            'string'   => 'is_string',
            'bool'     => 'is_bool',
            'boolean'  => 'is_bool',
            'scalar'   => 'is_scalar',
            'array'    => 'is_array',
            'object'   => 'is_object',
            'resource' => 'is_resource',
        );

        if ('mixed' === $typeDefinition) {
            return true;
        }

        if (array_key_exists($typeDefinition, $map)) {
            $function = $map[$typeDefinition];

            return $function($value);
        }

        return false;
    }
}
