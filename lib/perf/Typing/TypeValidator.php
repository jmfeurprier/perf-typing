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
     * var {string:string}
     */
    private $primaryTypeMap = array(
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
        'null'     => 'is_null',
    );

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

        if ($this->isPrimaryType($typeDefinition)) {
            return $this->isValidPrimaryType($typeDefinition, $value);
        }

        $matches = array();

        if (1 === preg_match('/^(.+)\[\]$/', $typeDefinition, $matches)) {
            if (!is_array($value)) {
                return false;
            }

            $valueTypeDefinition = $matches[1];

            foreach ($value as $subValue) {
                if (!$this->isValidPrimaryType($valueTypeDefinition, $subValue)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     *
     *
     * @param string $keyTypeDefinition
     * @param string $valueTypeDefinition
     * @param mixed $value
     * @return bool
     */
    private function isPrimaryType($typeDefinition)
    {
        return array_key_exists($typeDefinition, $this->primaryTypeMap);
    }

    /**
     *
     *
     * @param string $typeDefinition
     * @param mixed $value
     * @return bool
     */
    private function isValidPrimaryType($typeDefinition, $value)
    {
        $function = $this->primaryTypeMap[$typeDefinition];

        return $function($value);
    }
}
