<?php

namespace perf\Typing\Tree;

/**
 *
 *
 */
class NonIndexedArrayTypeNode implements TypeNode
{

    /**
     *
     *
     * @var TypeNode
     */
    private $valueTypeNode;

    /**
     *
     *
     * @param TypeNode $valueTypeNode
     * @return void
     */
    public function __construct(TypeNode $valueTypeNode)
    {
        $this->valueTypeNode = $valueTypeNode;
    }

    /**
     *
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
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
     *
     *
     * @return string
     */
    public function __toString()
    {
        return $this->valueTypeNode . '[]';
    }
}
