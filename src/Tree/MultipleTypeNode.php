<?php

namespace perf\TypeValidation\Tree;

class MultipleTypeNode implements TypeNode
{
    /**
     * @var TypeNode[]
     */
    private array $typeNodes = [];

    /**
     * @param TypeNode[] $typeNodes
     */
    public function __construct(array $typeNodes)
    {
        foreach ($typeNodes as $typeNode) {
            $this->addTypeNode($typeNode);
        }
    }

    /**
     * @param TypeNode $typeNode
     */
    private function addTypeNode(TypeNode $typeNode): void
    {
        $this->typeNodes[] = $typeNode;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($value): bool
    {
        foreach ($this->typeNodes as $typeNode) {
            if ($typeNode->isValid($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return join('|', $this->typeNodes);
    }
}
