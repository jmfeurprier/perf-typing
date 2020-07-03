<?php

namespace perf\TypeValidation\Parsing;

use perf\TypeValidation\Exception\InvalidTypeSpecificationException;
use perf\TypeValidation\Tree;
use perf\TypeValidation\Tree\TypeNode;

class Parser
{
    private const VALID_ARRAY_KEY_TYPES = [
        'mixed',
        'string',
        'int',
        'integer',
    ];


    private Tokenizer $tokenizer;

    /**
     * @var Token[]
     */
    private array $tokens = [];

    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @param string $typeSpecification
     *
     * @return TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    public function parse(string $typeSpecification): TypeNode
    {
        $this->tokens = $this->tokenizer->tokenize($typeSpecification);

        $nodes = [];

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
     * @return Tree\TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    private function parseIndexedArray(): TypeNode
    {
        $this->popNextToken();

        $keyTypeNode = $this->parseArrayKeyType();
        $this->parseColon();
        $valueTypeNode = $this->parseArrayValueType();

        $node = new Tree\MapTypeNode($keyTypeNode, $valueTypeNode);

        return $this->parseSquareBrackets($node);
    }

    /**
     * @return TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    private function parseArrayKeyType(): TypeNode
    {
        $token = $this->popNextToken();

        if (!$token->isLabel()) {
            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()}."
            );
        }

        $type = $token->getContent();

        if (!in_array($type, self::VALID_ARRAY_KEY_TYPES, true)) {
            throw new InvalidTypeSpecificationException(
                "Unexpected type '{$type}' for array key at offset {$token->getOffset()}."
            );
        }

        return new Tree\LeafTypeNode($type);
    }

    /**
     * @return void
     *
     * @throws InvalidTypeSpecificationException
     */
    private function parseColon(): void
    {
        if (!$this->hasNextToken()) {
            throw new InvalidTypeSpecificationException(
                'Premature end of type specification encountered.'
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
     * @return TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    private function parseArrayValueType(): TypeNode
    {
        $nodes = [];

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
     * @return TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    private function parseLabel(): TypeNode
    {
        $token = $this->popNextToken();

        $node = new Tree\LeafTypeNode($token->getContent());

        return $this->parseSquareBrackets($node);
    }

    /**
     *
     *
     * @param TypeNode $node
     *
     * @return TypeNode
     */
    private function parseSquareBrackets(TypeNode $node): TypeNode
    {
        while ($this->hasNextToken()) {
            if (!$this->getNextToken()->isSquareBrackets()) {
                break;
            }

            $this->popNextToken();

            $node = new Tree\CollectionTypeNode($node);
        }

        return $node;
    }

    /**
     * @param TypeNode[] $nodes
     *
     * @return TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    private function mergeTypeNodes(array $nodes): TypeNode
    {
        if (count($nodes) > 1) {
            return new Tree\MultipleTypeNode($nodes);
        }

        if (1 === count($nodes)) {
            $node = reset($nodes);

            return $node;
        }

        throw new InvalidTypeSpecificationException(
            'Failed to extract tokens from specification type.'
        );
    }

    private function getNextToken(): Token
    {
        return reset($this->tokens);
    }

    private function popNextToken(): Token
    {
        return array_shift($this->tokens);
    }

    private function hasNextToken(): bool
    {
        return (count($this->tokens) > 0);
    }
}
