<?php

namespace perf\Typing\Parsing;

use perf\Typing\InvalidTypeSpecificationException;
use perf\Typing\Tree;

/**
 *
 *
 */
class Parser
{

    /**
     *
     *
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     *
     *
     * @param Tokenizer $tokenizer
     * @return void
     */
    public function setTokenizer(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @return Tree\TypeNode[]
     * @throws InvalidTypeSpecificationException
     */
    public function parse($typeSpecification)
    {
        $this->tokens = $this->getTokenizer()->tokenize($typeSpecification);

        $nodes = array();

        while ($this->hasNextToken()) {
            $token = $this->getNextToken();

            if ($token->isLabel()) {
                $nodes[] = $this->parseLabel();
            } elseif ($token->isOpeningBracket()) {
                $nodes[] = $this->parseIndexedArray();
            } else {
                throw new InvalidTypeSpecificationException(
                    "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()}."
                );
            }

            if (!$this->hasNextToken()) {
                break;
            }

            $nextToken = $this->getNextToken();

            if (!$nextToken->isPipe()) {
                throw new InvalidTypeSpecificationException(
                    "Unexpected token '{$nextToken->getContent()}' at offset {$nextToken->getOffset()}."
                );
            }

            $this->popNextToken();

            // Trailing pipe (|) encountered?
            if (!$this->hasNextToken()) {
                throw new InvalidTypeSpecificationException(
                    "Premature end of type specification encountered."
                );
            }
        }

        return $this->mergeTypeNodes($nodes);
    }

    /**
     *
     *
     * @return Tree\TypeNode
     * @throws InvalidTypeSpecificationException
     */
    private function parseIndexedArray()
    {
        $this->popNextToken();

        $keyTypeNode = $this->parseArrayKeyType();
        $this->parseColon();
        $valueTypeNode = $this->parseArrayValueType();

        $node = new Tree\IndexedArrayTypeNode($keyTypeNode, $valueTypeNode);

        return $this->parseSquareBrackets($node);
    }

    /**
     *
     *
     * @return Tree\TypeNode
     */
    private function parseArrayKeyType()
    {
        static $validArrayKeyTypes = array(
            'mixed',
            'string',
            'int',
            'integer',
        );

        $token = $this->popNextToken();

        if (!$token->isLabel()) {
            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()}."
            );
        }

        $type = $token->getContent();

        if (!in_array($type, $validArrayKeyTypes, true)) {
            throw new InvalidTypeSpecificationException(
                "Unexpected type '{$type}' for array key at offset {$token->getOffset()}."
            );
        }

        return new Tree\LeafTypeNode($type);
    }

    /**
     *
     *
     * @return void
     * @throws InvalidTypeSpecificationException
     */
    private function parseColon()
    {
        if (!$this->hasNextToken()) {
            throw new InvalidTypeSpecificationException(
                "Premature end of type specification encountered."
            );
        }

        $token = $this->popNextToken();

        if (!$token->isColon()) {
            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()} (expected colon)."
            );
        }
    }

    /**
     *
     *
     * @return Tree\TypeNode
     * @throws InvalidTypeSpecificationException
     */
    private function parseArrayValueType()
    {
        $nodes = array();

        while ($this->hasNextToken()) {
            $token = $this->getNextToken();

            if ($token->isLabel()) {
                $nodes[] = $this->parseLabel();
            } elseif ($token->isOpeningBracket()) {
                $nodes[] = $this->parseIndexedArray();
            } else {
                throw new InvalidTypeSpecificationException(
                    "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()} (expected colon)."
                );
            }

            if (!$this->hasNextToken()) {
                throw new InvalidTypeSpecificationException(
                    "Premature end of type specification encountered."
                );
            }

            $nextToken = $this->getNextToken();

            if ($nextToken->isPipe()) {
                $this->popNextToken();
                continue;
            }

            if ($nextToken->isClosingBracket()) {
                $this->popNextToken();
                break;
            }

            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$nextToken->getContent()}' at offset {$nextToken->getOffset()}."
            );
        }

        return $this->mergeTypeNodes($nodes);
    }

    /**
     *
     *
     * @return Tree\TypeNode
     * @throws InvalidTypeSpecificationException
     */
    private function parseLabel()
    {
        $token = $this->popNextToken();

        $node = new Tree\LeafTypeNode($token->getContent());

        return $this->parseSquareBrackets($node);
    }

    /**
     *
     *
     * @param Tree\TypeNode $node
     * @return Tree\TypeNode
     */
    private function parseSquareBrackets(Tree\TypeNode $node)
    {
        while ($this->hasNextToken()) {
            if (!$this->getNextToken()->isSquareBrackets()) {
                break;
            }

            $this->popNextToken();

            $node = new Tree\NonIndexedArrayTypeNode($node);
        }

        return $node;
    }

    /**
     *
     *
     * @param Tree\Token[] $nodes
     * @return Tree\Token
     * @throws InvalidTypeSpecificationException
     */
    private function mergeTypeNodes(array $nodes)
    {
        if (count($nodes) > 1) {
            return new Tree\MultipleTypeNode($nodes);
        }

        if (1 === count($nodes)) {
            $node = reset($nodes);

            return $node;
        }

        throw new InvalidTypeSpecificationException(
            "Failed to extract tokens from specification type."
        );
    }

    /**
     *
     *
     * @return Tree\Token
     */
    private function getNextToken()
    {
        return reset($this->tokens);
    }

    /**
     *
     *
     * @return Tree\Token
     */
    private function popNextToken()
    {
        return array_shift($this->tokens);
    }

    /**
     *
     *
     * @return bool
     */
    private function hasNextToken()
    {
        return (count($this->tokens) > 0);
    }

    /**
     *
     * Lazy getter.
     *
     * @return Tokenizer
     */
    private function getTokenizer()
    {
        if (!$this->tokenizer) {
            $this->setTokenizer(new Tokenizer());
        }

        return $this->tokenizer;
    }
}
