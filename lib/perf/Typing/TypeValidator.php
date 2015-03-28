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
     * var TypeSpecificationParser
     */
    private $typeSpecificationParser;

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @return bool
     * @throws InvalidTypeSpecificationException
     */
    public function isValid($typeSpecification, $value)
    {
        if (!is_string($typeSpecification)) {
            throw new InvalidTypeSpecificationException('Invalid type specification provided (expected string).');
        }

        if (1 !== preg_match('/^[a-zA-Z\d\\{\\}\\[\\]\\_\\:\|\\\\]+$/', $typeSpecification)) {
            throw new InvalidTypeSpecificationException('Invalid type specification provided (invalid characters).');
        }

        foreach ($this->getTypeSpecificationParser()->parse($typeSpecification) as $alternativeType) {
            if ($this->processAlternative($alternativeType, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @return bool
     */
    private function processAlternative($typeSpecification, $value) {
        if ($this->isPrimaryType($typeSpecification)) {
            return $this->isValidPrimaryType($typeSpecification, $value);
        }

        // Non-indexed array type
        $matches = array();

        if (1 === preg_match('/^(.+)\[\]$/', $typeSpecification, $matches)) {
            if (!is_array($value)) {
                return false;
            }

            $valueTypeSpecification = $matches[1];

            foreach ($value as $subValue) {
                if (!$this->isValidPrimaryType($valueTypeSpecification, $subValue)) {
                    return false;
                }
            }

            return true;
        }

        // Indexed array type
        $matches = array();

        if (1 === preg_match('/^\{(.+)\:(.+)\}$/', $typeSpecification, $matches)) {
            $keyTypeSpecification = $matches[1];

            $validArrayKeyTypes = array(
                'mixed',
                'int',
                'integer',
                'string',
            );

            if (!in_array($keyTypeSpecification, $validArrayKeyTypes, true)) {
                throw new InvalidTypeSpecificationException(
                    'Invalid array key type specification (expected integer or string).'
                );
            }

            if (!is_array($value)) {
                return false;
            }

            $valueTypeSpecification = $matches[2];

            foreach ($value as $key => $subValue) {
                if (!$this->isValidPrimaryType($keyTypeSpecification, $key)) {
                    return false;
                }

                if (!$this->isValidPrimaryType($valueTypeSpecification, $subValue)) {
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
     * @param string $keyTypeSpecification
     * @param string $valueTypeSpecification
     * @param mixed $value
     * @return bool
     */
    private function isPrimaryType($typeSpecification)
    {
        if ('mixed' === $typeSpecification) {
            return true;
        }

        return array_key_exists($typeSpecification, $this->primaryTypeMap);
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @return bool
     */
    private function isValidPrimaryType($typeSpecification, $value)
    {
        if ('mixed' === $typeSpecification) {
            return true;
        }

        $function = $this->primaryTypeMap[$typeSpecification];

        return $function($value);
    }

    /**
     *
     *
     * @return TypeSpecificationParser
     */
    private function getTypeSpecificationParser()
    {
        if (!$this->typeSpecificationParser) {
            $this->typeSpecificationParser = new TypeSpecificationParser();
        }

        return $this->typeSpecificationParser;
    }
}
