<?php

namespace perf\TypeValidation\Tree;

/**
 *
 *
 */
class MultipleTypeNode implements TypeNode
{

    /**
     *
     *
     * @var TypeNode[]
     */
    private $typeNodes = array();

    /**
     * Constructor.
     *
     * @param TypeNode[] $typeNodes
     * @return void
     */
    public function __construct(array $typeNodes)
    {
        foreach ($typeNodes as $typeNode) {
            $this->addTypeNode($typeNode);
        }
    }

    /**
     *
     *
     * @param TypeNode $typeNode
     * @return void
     */
    private function addTypeNode(TypeNode $typeNode)
    {
        $this->typeNodes[] = $typeNode;
    }

    /**
     * Tells wether provided value is valid according to current type node.
     *
     * @param mixed $value Value to validate.
     * @return bool
     */
    public function isValid($value)
    {
        foreach ($this->typeNodes as $typeNode) {
            if ($typeNode->isValid($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a textual representation of the current type node.
     *
     * @return string
     */
    public function __toString()
    {
        return join('|', $this->typeNodes);
    }
}
