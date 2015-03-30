<?php

namespace perf\Typing\Tree;

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
     *
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
     *
     *
     * @param mixed $value
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
     *
     *
     * @return string
     */
    public function __toString()
    {
        return join('|', $this->typeNodes);
    }
}
