<?php

namespace perf\Typing;

use perf\Typing\Parsing\Parser;

/**
 *
 *
 */
class TypeValidator
{

    /**
     *
     *
     * @var Parser
     */
    private $parser;

    /**
     *
     *
     * @param Parser $parser
     * @return void
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValid($typeSpecification, $value)
    {
        if (!is_string($typeSpecification)) {
            throw new InvalidTypeSpecificationException(
                "Invalid type specification provided (expected string)."
            );
        }

        $typeTree = $this->getTypeTree($typeSpecification);

        return $typeTree->isValid($value);
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @return TypeTree
     * @throws \InvalidArgumentException
     */
    private function getTypeTree($typeSpecification)
    {
        return $this->getParser()->parse($typeSpecification);
    }

    /**
     *
     *
     * @return Parser
     */
    private function getParser()
    {
        if (!$this->parser) {
            $this->setParser(new Parser());
        }

        return $this->parser;
    }
}
