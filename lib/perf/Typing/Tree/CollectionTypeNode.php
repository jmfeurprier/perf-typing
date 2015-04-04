<?php

namespace perf\Typing\Tree;

/**
 *
 *
 */
class CollectionTypeNode implements TypeNode
{

    /**
     *
     *
     * @var TypeNode
     */
    private $valueTypeNode;

    /**
     * Constructor.
     *
     * @param TypeNode $valueTypeNode
     * @return void
     */
    public function __construct(TypeNode $valueTypeNode)
    {
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
