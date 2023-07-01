<?php

namespace Jmf\TypeValidation\Tree;

class MultipleTypeNode implements TypeNode
{
    /**
     * @var TypeNode[]
     */
    private array $typeNodes = [];

    /**
     * @param TypeNode[] $typeNodes
     */
    public function __construct(
        array $typeNodes
    ) {
        foreach ($typeNodes as $typeNode) {
            $this->addTypeNode($typeNode);
        }
    }

    private function addTypeNode(TypeNode $typeNode): void
    {
        $this->typeNodes[] = $typeNode;
    }

    public function isValid(mixed $value): bool
    {
        foreach ($this->typeNodes as $typeNode) {
            if ($typeNode->isValid($value)) {
                return true;
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return implode('|', $this->typeNodes);
    }
}
