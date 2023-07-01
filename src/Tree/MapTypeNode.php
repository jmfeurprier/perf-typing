<?php

namespace Jmf\TypeValidation\Tree;

readonly class MapTypeNode implements TypeNode
{
    public function __construct(
        private TypeNode $keyTypeNode,
        private TypeNode $valueTypeNode
    ) {
    }

    public function isValid(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $subKey => $subValue) {
            if (!$this->keyTypeNode->isValid($subKey)) {
                return false;
            }

            if (!$this->valueTypeNode->isValid($subValue)) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return '{' . $this->keyTypeNode . ':' . $this->valueTypeNode . '}';
    }
}
