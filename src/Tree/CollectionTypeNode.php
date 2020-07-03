<?php

namespace perf\TypeValidation\Tree;

class CollectionTypeNode implements TypeNode
{
    private TypeNode $valueTypeNode;

    public function __construct(TypeNode $valueTypeNode)
    {
        $this->valueTypeNode = $valueTypeNode;
    }

    /**
     * Tells whether provided value is valid according to current type node.
     *
     * @param mixed $value Value to validate.
     *
     * @return bool
     */
    public function isValid($value): bool
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

    /**
     * Returns a textual representation of the current type node.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->valueTypeNode . '[]';
    }
}
