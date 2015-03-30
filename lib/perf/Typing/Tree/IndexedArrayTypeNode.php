<?php

namespace perf\Typing\Tree;

/**
 *
 *
 */
class IndexedArrayTypeNode implements TypeNode
{

    /**
     *
     *
     * @var TypeNode
     */
    private $keyTypeNode;

    /**
     *
     *
     * @var TypeNode
     */
    private $valueTypeNode;

    /**
     *
     *
     * @param TypeNode $keyTypeNode
     * @param TypeNode $valueTypeNode
     * @return void
     */
    public function __construct(TypeNode $keyTypeNode, TypeNode $valueTypeNode)
    {
        $this->keyTypeNode   = $keyTypeNode;
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
     *
     *
     * @return string
     */
    public function __toString()
    {
        return '{' . $this->keyTypeNode . ':' . $this->valueTypeNode . '}';
    }
}
