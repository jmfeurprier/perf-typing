<?php

namespace perf\Typing\Tree;

/**
 *
 *
 */
class IndexedArrayTypeNode implements TypeNode
{

    /**
     * Type node for the key part of an indexed array.
     *
     * @var TypeNode
     */
    private $keyTypeNode;

    /**
     * Type node for the value part of an indexed array.
     *
     * @var TypeNode
     */
    private $valueTypeNode;

    /**
     * Constructor.
     *
     * @param TypeNode $keyTypeNode Key part type node.
     * @param TypeNode $valueTypeNode Value part type node.
     * @return void
     */
    public function __construct(TypeNode $keyTypeNode, TypeNode $valueTypeNode)
    {
        $this->keyTypeNode   = $keyTypeNode;
        $this->valueTypeNode = $valueTypeNode;
    }

    /**
     * Tells wether provided value is valid according to current type node.
     *
     * @param mixed $value Value to validate.
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
     * Returns a textual representation of the current type node.
     *
     * @return string
     */
    public function __toString()
    {
        return '{' . $this->keyTypeNode . ':' . $this->valueTypeNode . '}';
    }
}
