<?php

namespace perf\Typing;

use perf\Typing\Parsing\TypeSpecificationParser;
use perf\Typing\Exception\InvalidTypeSpecificationException;

/**
 *
 *
 */
class TypeValidator
{

    /**
     *
     *
     * @var TypeSpecificationParser
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

        $typeTree = $this->getTypeSpecificationParser()->parse($typeSpecification);

        return $typeTree->isValid($value);
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
