<?php

namespace Jmf\TypeValidation\Tree;

readonly class CollectionTypeNode implements TypeNode
{
    public function __construct(
        private TypeNode $valueTypeNode
    ) {
    }

    public function isValid(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $subValue) {
            if (!$this->valueTypeNode->isValid($subValue)) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->valueTypeNode . '[]';
    }
}
