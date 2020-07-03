<?php

namespace perf\TypeValidation\Tree;

class MapTypeNode implements TypeNode
{
    private TypeNode $keyTypeNode;

    private TypeNode $valueTypeNode;

    public function __construct(TypeNode $keyTypeNode, TypeNode $valueTypeNode)
    {
        $this->keyTypeNode   = $keyTypeNode;
        $this->valueTypeNode = $valueTypeNode;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($value): bool
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

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return '{' . $this->keyTypeNode . ':' . $this->valueTypeNode . '}';
    }
}
