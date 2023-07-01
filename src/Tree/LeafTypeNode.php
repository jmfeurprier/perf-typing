<?php

namespace Jmf\TypeValidation\Tree;

readonly class LeafTypeNode implements TypeNode
{
    private const FUNCTION_MAP = [
        'array'    => 'is_array',
        'bool'     => 'is_bool',
        'boolean'  => 'is_bool',
        'double'   => 'is_float',
        'float'    => 'is_float',
        'int'      => 'is_int',
        'integer'  => 'is_int',
        'null'     => 'is_null',
        'numeric'  => 'is_numeric',
        'object'   => 'is_object',
        'resource' => 'is_resource',
        'scalar'   => 'is_scalar',
        'string'   => 'is_string',
    ];

    public function __construct(
        private string $type
    ) {
    }

    public function isValid(mixed $value): bool
    {
        if ('mixed' === $this->type) {
            return true;
        }

        if (array_key_exists($this->type, self::FUNCTION_MAP)) {
            $function = self::FUNCTION_MAP[$this->type];

            return $function($value);
        }

        if (is_object($value)) {
            return ($value instanceof $this->type);
        }

        return false;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
